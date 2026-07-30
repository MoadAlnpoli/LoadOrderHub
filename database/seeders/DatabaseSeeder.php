<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\ModPack;
use App\Models\Mod;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean initial structure.
     */
    public function run(): void
    {
        // 1. Create Main Admin Account
        $admin = User::firstOrCreate(
            ['email' => 'moadnp@gmail.com'],
            [
                'name' => 'Moad Admin',
                'password' => bcrypt('moad1234'),
                'is_admin' => true,
            ]
        );
        $admin->update(['is_admin' => true]);

        // 2. Create 20 Popular Supported Games with Real Data & Steam Images
        $gamesData = [
            ['slug' => 'skyrim-special-edition', 'name' => 'Skyrim Special Edition', 'description' => 'The legendary fantasy masterpiece from Bethesda Game Studios.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/489830/header.jpg'],
            ['slug' => 'mount-and-blade-ii-bannerlord', 'name' => 'Mount & Blade II: Bannerlord', 'description' => 'A strategy/action RPG that lets you lead armies and build empires.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/261550/header.jpg'],
            ['slug' => 'cyberpunk-2077', 'name' => 'Cyberpunk 2077', 'description' => 'An open-world, action-adventure RPG set in the dark future of Night City.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1091500/header.jpg'],
            ['slug' => 'the-witcher-3-wild-hunt', 'name' => 'The Witcher 3: Wild Hunt', 'description' => 'Become Geralt of Rivia, a professional monster slayer in a vast open world.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg'],
            ['slug' => 'fallout-4', 'name' => 'Fallout 4', 'description' => 'Bethesda Game Studios open-world post-apocalyptic survival RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/377160/header.jpg'],
            ['slug' => 'starfield', 'name' => 'Starfield', 'description' => 'The next-generation space exploration role-playing game.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1716740/header.jpg'],
            ['slug' => 'elden-ring', 'name' => 'Elden Ring', 'description' => 'The action RPG fantasy epic created by FromSoftware and George R. R. Martin.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1245620/header.jpg'],
            ['slug' => 'grand-theft-auto-v', 'name' => 'Grand Theft Auto V', 'description' => 'Explore the vast award-winning world of Los Santos and Blaine County.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/271590/header.jpg'],
            ['slug' => 'red-dead-redemption-2', 'name' => 'Red Dead Redemption 2', 'description' => 'Arthur Morgan and the Van der Linde gang are outlaws on the run across America.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg'],
            ['slug' => 'baldurs-gate-3', 'name' => 'Baldur\'s Gate 3', 'description' => 'Gather your party and return to the Forgotten Realms in a next-generation RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1086940/header.jpg'],
            ['slug' => 'hogwarts-legacy', 'name' => 'Hogwarts Legacy', 'description' => 'Experience Hogwarts in the 1800s. Your character is a student holding the key to an ancient secret.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/990080/header.jpg'],
            ['slug' => 'monster-hunter-world', 'name' => 'Monster Hunter: World', 'description' => 'Welcome to a new world! In Monster Hunter: World, enjoy the ultimate hunting experience.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/582010/header.jpg'],
            ['slug' => 'stardew-valley', 'name' => 'Stardew Valley', 'description' => 'You\'ve inherited your grandfather\'s old farm plot in Stardew Valley.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg'],
            ['slug' => 'fallout-new-vegas', 'name' => 'Fallout: New Vegas', 'description' => 'Feel the heat in New Vegas! Enjoy the ultimate post-apocalyptic Vegas experience.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/22380/header.jpg'],
            ['slug' => 'rimworld', 'name' => 'RimWorld', 'description' => 'A sci-fi colony sim driven by an intelligent AI storyteller.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/294100/header.jpg'],
            ['slug' => 'kingdom-come-deliverance', 'name' => 'Kingdom Come: Deliverance', 'description' => 'Story-driven open-world RPG that immerses you in an epic adventure in Bohemia.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/379430/header.jpg'],
            ['slug' => 'valheim', 'name' => 'Valheim', 'description' => 'A brutal exploration and survival game for 1-10 players set in a procedurally generated purgatory.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/892970/header.jpg'],
            ['slug' => 'dark-souls-iii', 'name' => 'DARK SOULS III', 'description' => 'Dark Souls continues to push the boundaries with the latest, ambitious chapter in the critically-acclaimed series.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/374320/header.jpg'],
            ['slug' => 'dragons-dogma-2', 'name' => 'Dragon\'s Dogma 2', 'description' => 'Dragon\'s Dogma 2 is a single player, narrative driven action-RPG.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/2054970/header.jpg'],
            ['slug' => 'star-wars-jedi-survivor', 'name' => 'STAR WARS Jedi: Survivor', 'description' => 'The story of Cal Kestis continues in Star Wars Jedi: Survivor.', 'thumbnail' => 'https://shared.fastly.steamstatic.com/store_item_assets/steam/apps/1770590/header.jpg'],
        ];

        $gameModels = [];
        foreach ($gamesData as $gData) {
            $gModel = Game::firstOrCreate(['slug' => $gData['slug']], $gData);
            $gameModels[] = $gModel;
            // Version
            GameVersion::firstOrCreate(['game_id' => $gModel->id, 'version' => '1.0']);
            GameVersion::firstOrCreate(['game_id' => $gModel->id, 'version' => '1.5.0']);
        }

        // 3. Create Featured Mod Packs (1 per game = 20 ModPacks)
        $modPackModels = [];
        foreach ($gameModels as $index => $gModel) {
            $pack = ModPack::firstOrCreate(
                ['title_en' => "{$gModel->name} Ultimate Overhaul Modpack #" . ($index + 1)],
                [
                    'title_ar' => "تجميعة المودات الكاملة والشاملة للعبة {$gModel->name}",
                    'description_en' => "Complete collection of visual, framework, and performance mods for {$gModel->name}.",
                    'description_ar' => "تجميعة شاملة من أفضل المودات المحسنة والأطر البرمجية والرسومات للعبة {$gModel->name}.",
                    'youtube_video_id' => 'dQw4w9WgXcQ',
                    'youtube_thumbnail_url' => $gModel->thumbnail,
                    'local_thumbnail_path' => null,
                    'views_count' => rand(500, 10000),
                    'upvotes' => rand(50, 900),
                    'downvotes' => rand(0, 15),
                    'is_published' => true,
                    'created_by' => $admin->id,
                ]
            );
            $vIds = $gModel->versions()->pluck('id')->toArray();
            if (!empty($vIds)) {
                $pack->gameVersions()->sync($vIds);
            }
            $modPackModels[$gModel->id] = $pack;
        }

        // 4. Generate 1,000+ Curated Mods distributed across 20 Games
        $modPrefixes = ['Ultimate', 'Enhanced', 'HD Re-texture', 'Script Extender', 'Performance Boost', '4K Textures', 'Realistic Weather', 'Overhaul Framework', 'Community Patch', 'Immersive Audio', 'Fast Travel Fix', 'Custom UI Interface', 'Advanced AI Engine', 'Lighting Overhaul', 'RayTracing Reshade', 'Next-Gen Combat', 'Expanded Inventory', 'Ultra Shadows', 'Expanded Magic & Spells', 'Dynamic Camera FX', 'Survival Realism', 'LOD Generator', 'FPS Unlocked', 'True Weather FX', 'Seamless Co-op Engine'];
        $modCategories = ['Graphics', 'Fixes', 'Gameplay', 'Audio', 'Interface', 'Framework', 'Combat', 'Quests', 'Weapons', 'Outfits'];

        $totalModsCount = Mod::count();
        if ($totalModsCount < 1000) {
            foreach ($gameModels as $gModel) {
                $pack = $modPackModels[$gModel->id] ?? null;
                $gameVersionIds = $gModel->versions()->pluck('id')->toArray();

                for ($i = 1; $i <= 55; $i++) {
                    $prefix = $modPrefixes[array_rand($modPrefixes)];
                    $modName = "{$prefix} for {$gModel->name} Part {$i}";
                    $category = $modCategories[array_rand($modCategories)];

                    $mod = Mod::firstOrCreate(
                        [
                            'game_id' => $gModel->id,
                            'name'    => $modName,
                        ],
                        [
                            'mod_pack_id'      => $pack?->id,
                            'slug'             => Str::slug($modName) . '-' . rand(100, 9999),
                            'description'      => "High performance {$category} modification for {$gModel->name}. Tested for maximum compatibility and zero crashes.",
                            'author'           => 'Nexus Modder ' . rand(10, 99),
                            'version'          => '1.' . rand(0, 9) . '.' . rand(0, 9),
                            'load_order'       => $i,
                            'nexus_url'        => "https://www.nexusmods.com/{$gModel->slug}/mods/" . rand(1000, 99999),
                            'download_url'     => "https://www.nexusmods.com/{$gModel->slug}/mods/" . rand(1000, 99999),
                            'image_url'        => $gModel->thumbnail,
                            'status'           => 'published',
                            'downloads_count'  => rand(500, 50000),
                            'fps_impact'       => rand(0, 15),
                            'is_verified'      => true,
                        ]
                    );

                    if (!empty($gameVersionIds)) {
                        $mod->gameVersions()->syncWithoutDetaching($gameVersionIds);
                    }
                }
            }
        }

        // 5. Populate Default Ad Slots
        if (\Illuminate\Support\Facades\Schema::hasTable('ad_slots')) {
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'الصفحة الرئيسية - أعلى المبنى'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'مستكشف المودات - الشريط الجانبي'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
            \App\Models\AdSlot::firstOrCreate(
                ['name' => 'تفاصيل المود - أسفل الرابط'],
                ['impressions' => 0, 'clicks' => 0, 'is_active' => true]
            );
        }
    }
}
