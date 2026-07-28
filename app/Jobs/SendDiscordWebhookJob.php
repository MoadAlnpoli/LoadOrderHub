<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ModPack;

class SendDiscordWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $modPack;

    public function __construct(ModPack $modPack)
    {
        $this->modPack = $modPack;
    }

    public function handle()
    {
        $webhookUrl = env('DISCORD_WEBHOOK_URL');
        if (!$webhookUrl) {
            return;
        }

        if ($this->modPack->discord_webhook_sent) {
            return;
        }

        try {
            $data = [
                'content' => "New ModPack published: **{$this->modPack->title_en}**!",
                'embeds' => [
                    [
                        'title' => $this->modPack->title_en,
                        'description' => strip_tags($this->modPack->description_en),
                        'url' => url("/packs/{$this->modPack->slug}"),
                        'color' => 7506394, // Blurple
                        'image' => [
                            'url' => url($this->modPack->image_url ?? '/images/default_pack.jpg')
                        ]
                    ]
                ]
            ];

            $response = Http::post($webhookUrl, $data);

            if ($response->successful()) {
                $this->modPack->update(['discord_webhook_sent' => true]);
                Log::info("Discord webhook sent for pack {$this->modPack->id}");
            } else {
                Log::error("Discord webhook failed for pack {$this->modPack->id}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Discord webhook exception for pack {$this->modPack->id}: " . $e->getMessage());
        }
    }
}
