<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync Nexus Mods data daily at 3 AM
        $schedule->job(\App\Jobs\SyncNexusModsJob::class)
                 ->dailyAt('03:00')
                 ->name('sync-nexus-mods')
                 ->withoutOverlapping();

        // AUTO-IMPORT top mods daily at 6 AM (endorsements)
        $schedule->job(new \App\Jobs\AutoImportNexusModsJob('endorsements'))
                 ->dailyAt('06:00')
                 ->name('auto-import-nexus-top')
                 ->withoutOverlapping();

        // AUTO-IMPORT latest added mods daily at 7 AM
        $schedule->job(new \App\Jobs\AutoImportNexusModsJob('latest_added'))
                 ->dailyAt('07:00')
                 ->name('auto-import-nexus-latest')
                 ->withoutOverlapping();

        // Check game version updates daily at 4 AM
        $schedule->job(\App\Jobs\CheckGameUpdatesJob::class)
                 ->dailyAt('04:00')
                 ->name('check-game-updates')
                 ->withoutOverlapping();

        // Database backup every Sunday at 2 AM
        $schedule->job(\App\Jobs\BackupDatabaseJob::class)
                 ->weekly()
                 ->sundays()
                 ->at('02:00')
                 ->name('weekly-db-backup')
                 ->withoutOverlapping();

        // Weekly newsletter + Discord top mods every Friday at 12 PM
        $schedule->job(\App\Jobs\SendWeeklyNewsletterJob::class)
                 ->weekly()
                 ->fridays()
                 ->at('12:00')
                 ->name('weekly-newsletter')
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
