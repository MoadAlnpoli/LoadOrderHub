<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mod;
use App\Models\GameVersion;
use App\Services\NexusSearchService;

class EnrichModsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mods:enrich {--limit=10 : The maximum number of mods to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically search and enrich mods with images, descriptions, steam URLs, and compatible versions from Nexus Mods';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        
        $this->info("Starting mod enrichment process (limit: {$limit})...");

        // Find mods that lack image_url OR description, ordered by least recently updated to rotate queue
        $mods = Mod::where(function($query) {
                $query->whereNull('image_url')
                      ->orWhereNull('description');
            })
            ->with(['game', 'gameVersions'])
            ->orderBy('updated_at', 'asc')
            ->take($limit)
            ->get();

        if ($mods->isEmpty()) {
            $this->info("No mods need enrichment at the moment.");
            return 0;
        }

        $enrichedCount = 0;

        foreach ($mods as $mod) {
            $this->info("--------------------------------------------------");
            $this->info("Processing Mod: {$mod->name} [ID: {$mod->id}] for Game: " . ($mod->game->name ?? 'None'));

            $nexusUrl = $mod->nexus_url;

            // Step 1: If no nexus_url, search DuckDuckGo via Jina proxy
            if (empty($nexusUrl) && $mod->game) {
                $this->comment("No Nexus URL found. Searching Nexus Mods...");
                $searchResults = NexusSearchService::searchMod($mod->game->slug, $mod->name);
                
                if (!empty($searchResults)) {
                    $topMatch = $searchResults[0];
                    $nexusUrl = $topMatch['url'];
                    $mod->nexus_url = $nexusUrl;
                    $this->info("Found Nexus Mods match: {$nexusUrl}");
                } else {
                    $this->warn("No search matches found on Nexus Mods.");
                }
            }

            if (empty($nexusUrl)) {
                $this->warn("Skipping mod: No Nexus page URL available.");
                continue;
            }

            // Step 2: Fetch known versions for this game
            $knownVersions = [];
            if ($mod->game) {
                $knownVersions = GameVersion::where('game_id', $mod->game_id)
                    ->pluck('version')
                    ->toArray();
            }

            // Step 3: Scrape details from Nexus Mod page
            $this->comment("Fetching details from Nexus Mod page...");
            $details = NexusSearchService::getModDetails($nexusUrl, $knownVersions);

            // Step 4: Update mod metadata
            $updated = false;

            if ($details['image_url'] && $details['image_url'] !== $mod->image_url) {
                $mod->image_url = $details['image_url'];
                $updated = true;
                $this->info("Updated Image URL: {$mod->image_url}");
            }

            if ($details['description'] && $details['description'] !== $mod->description) {
                $mod->description = $details['description'];
                $updated = true;
                $this->info("Updated Description: " . substr($mod->description, 0, 50) . "...");
            }

            if ($details['steam_url'] && empty($mod->steam_url)) {
                $mod->steam_url = $details['steam_url'];
                $updated = true;
                $this->info("Updated Steam Workshop URL: {$mod->steam_url}");
            }

            if ($updated || $mod->isDirty()) {
                $mod->save();
                $enrichedCount++;
            } else {
                // Force update the timestamp to rotate the queue
                $mod->touch();
            }

            // Step 5: Auto-link matched game versions compatibility
            if (!empty($details['matched_versions']) && $mod->game) {
                $this->comment("Found compatible versions in description: " . implode(', ', $details['matched_versions']));
                
                $versionIds = GameVersion::where('game_id', $mod->game_id)
                    ->whereIn('version', $details['matched_versions'])
                    ->pluck('id')
                    ->toArray();

                if (!empty($versionIds)) {
                    // Sync without detaching existing ones
                    $mod->gameVersions()->syncWithoutDetaching($versionIds);
                    $this->info("Linked mod to versions count: " . count($versionIds));
                }
            }
        }

        $this->info("==================================================");
        $this->info("Enrichment completed. Enriched {$enrichedCount} mods successfully.");

        return 0;
    }
}
