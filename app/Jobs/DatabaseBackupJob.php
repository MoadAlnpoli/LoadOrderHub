<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DatabaseBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        try {
            $database = env('DB_DATABASE');
            $user = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            
            $date = date('Y-m-d_H-i-s');
            $filename = "backup_{$database}_{$date}.sql.gz";
            $path = storage_path("app/backups");
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $fullpath = "{$path}/{$filename}";
            
            // Note: requires mysqldump and gzip to be installed on server
            $command = "mysqldump -h {$host} -u {$user} -p'{$password}' {$database} | gzip > {$fullpath}";
            
            $result = null;
            $output = [];
            exec($command, $output, $result);
            
            if ($result === 0) {
                Log::info("Database backup created successfully at {$fullpath}");
            } else {
                Log::error("Database backup failed. Error code: {$result}");
            }
        } catch (\Exception $e) {
            Log::error("Database backup exception: " . $e->getMessage());
        }
    }
}
