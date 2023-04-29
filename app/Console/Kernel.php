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
        $schedule->command('proclubs:matches')
            ->everyFifteenMinutes()
            ->description('Get latest matches data')
            ->runInBackground();

        $schedule->command('proclubs:players')
            ->daily()
            ->description('Process inactive players')
            ->runInBackground();

        $schedule->command('backup:clean', ['--disable-notifications'])
            ->dailyAt('04:00')
            ->description('Clean up old backups')
            ->runInBackground();

        $schedule->command('backup:run', ['--only-db'])
            ->dailyAt('04:00')
            ->description('Backup database')
            ->runInBackground();
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
