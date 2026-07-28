<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting database backup job...');

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        
        $date = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$date}.sql";
        $storagePath = storage_path("app/backups");

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        // Using mysqldump - requires it to be in PATH
        $passwordArg = $dbPass ? "-p\"{$dbPass}\"" : "";
        $command = "mysqldump -h {$dbHost} -P {$dbPort} -u {$dbUser} {$passwordArg} {$dbName} > \"{$filePath}\"";

        $output = null;
        $result = null;
        exec($command, $output, $result);

        if ($result === 0) {
            Log::info("Database backup completed successfully: {$filename}");
            // Send Discord Notification
            $this->notifyDiscord("✅ Database backup completed successfully. File: `{$filename}`");
        } else {
            Log::error("Database backup failed with code {$result}. Command: {$command}");
            $this->notifyDiscord("❌ Database backup failed. Check logs for details.");
        }
    }

    protected function notifyDiscord($message)
    {
        $webhookUrl = env('DISCORD_WEBHOOK_URL');
        
        if (!$webhookUrl) {
            Log::warning('Discord webhook URL not configured.');
            return;
        }

        try {
            \Illuminate\Support\Facades\Http::post($webhookUrl, [
                'content' => $message,
                'username' => 'ModPack Admin Bot',
                'avatar_url' => 'https://laravel.com/img/logomark.min.svg'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send Discord notification: ' . $e->getMessage());
        }
    }
}
