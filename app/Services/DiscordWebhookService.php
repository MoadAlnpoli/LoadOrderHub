<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Mod;
use App\Models\ModPack;

class DiscordWebhookService
{
    protected ?string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = env('DISCORD_WEBHOOK_URL');
    }

    protected function send(array $payload): void
    {
        if (!$this->webhookUrl) return;

        try {
            Http::post($this->webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('Discord webhook error: ' . $e->getMessage());
        }
    }

    public function announceNewMod(Mod $mod): void
    {
        $gameIcon = '🎮';
        $this->send([
            'username'   => 'LoadOrderHub Bot',
            'avatar_url' => 'https://cdn-icons-png.flaticon.com/512/3082/3082008.png',
            'embeds'     => [[
                'title'       => "{$gameIcon} New Mod Added: {$mod->name}",
                'description' => \Str::limit(strip_tags($mod->description ?? ''), 200),
                'url'         => url('/mods/' . $mod->slug),
                'color'       => 0x7c3aed,
                'fields'      => [
                    ['name' => 'Game',    'value' => $mod->game->name ?? 'Unknown', 'inline' => true],
                    ['name' => 'Author',  'value' => $mod->author   ?? 'Unknown',   'inline' => true],
                    ['name' => 'Version', 'value' => $mod->version  ?? 'N/A',       'inline' => true],
                ],
                'thumbnail'   => $mod->image_url ? ['url' => $mod->image_url] : null,
                'footer'      => ['text' => 'LoadOrderHub • ' . now()->format('Y-m-d')],
            ]],
        ]);
    }

    public function announceNewPack(ModPack $pack): void
    {
        $title  = app()->getLocale() === 'ar' ? $pack->title_ar : $pack->title_en;
        $desc   = app()->getLocale() === 'ar' ? $pack->description_ar : $pack->description_en;
        $packId = $pack->id;

        $this->send([
            'username'   => 'LoadOrderHub Bot',
            'avatar_url' => 'https://cdn-icons-png.flaticon.com/512/3082/3082008.png',
            'embeds'     => [[
                'title'       => "📦 New Pack Published: {$title}",
                'description' => \Str::limit(strip_tags($desc ?? ''), 200),
                'url'         => url("/packs/{$packId}"),
                'color'       => 0x0ea5e9,
                'fields'      => [
                    ['name' => 'Creator', 'value' => $pack->creator->name ?? 'Unknown', 'inline' => true],
                    ['name' => 'Mods',    'value' => $pack->mods()->count() . ' mods',  'inline' => true],
                ],
                'footer'      => ['text' => 'LoadOrderHub • ' . now()->format('Y-m-d')],
            ]],
        ]);
    }

    public function announceWeeklyTop(array $topMods): void
    {
        if (empty($topMods)) return;

        $list = '';
        foreach (array_slice($topMods, 0, 5) as $i => $mod) {
            $medal = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'][$i] ?? '▪️';
            $list .= "{$medal} **{$mod['name']}** — " . number_format($mod['downloads_count'] ?? 0) . " downloads\n";
        }

        $this->send([
            'username'   => 'LoadOrderHub Bot',
            'avatar_url' => 'https://cdn-icons-png.flaticon.com/512/3082/3082008.png',
            'embeds'     => [[
                'title'       => '🏆 Top Mods This Week on LoadOrderHub',
                'description' => $list,
                'url'         => url('/mods-explorer'),
                'color'       => 0xf59e0b,
                'footer'      => ['text' => 'LoadOrderHub Weekly Recap • ' . now()->format('Y-m-d')],
            ]],
        ]);
    }
}
