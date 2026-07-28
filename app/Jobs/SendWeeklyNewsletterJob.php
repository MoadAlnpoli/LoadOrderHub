<?php

namespace App\Jobs;

use App\Models\Mod;
use App\Models\NewsletterSubscriber;
use App\Services\DiscordWebhookService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWeeklyNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(DiscordWebhookService $discord): void
    {
        Log::info('SendWeeklyNewsletterJob: starting...');

        // Get top 10 mods by downloads this week
        $topMods = Mod::where('status', 'published')
            ->orderBy('downloads_count', 'desc')
            ->take(10)
            ->with('game')
            ->get();

        // Post to Discord
        $discord->announceWeeklyTop($topMods->map(fn($m) => [
            'name'            => $m->name,
            'downloads_count' => $m->downloads_count,
        ])->toArray());

        // Send emails if newsletter table exists
        try {
            $subscribers = NewsletterSubscriber::where('is_active', true)->get();
            foreach ($subscribers as $sub) {
                // Simple mail - extend with a proper Mailable if needed
                Mail::raw(
                    "This week's top mods on LoadOrderHub:\n\n" .
                    $topMods->map(fn($m, $i) => ($i+1) . ". {$m->name} ({$m->game->name})")->implode("\n") .
                    "\n\nVisit: " . url('/mods-explorer') .
                    "\n\nUnsubscribe: " . url('/newsletter/unsubscribe/' . $sub->token),
                    fn($msg) => $msg
                        ->to($sub->email)
                        ->subject('🎮 Top Mods This Week — LoadOrderHub')
                );
            }
            Log::info("SendWeeklyNewsletterJob: sent to {$subscribers->count()} subscribers.");
        } catch (\Exception $e) {
            Log::warning('Newsletter email send failed: ' . $e->getMessage());
        }
    }
}
