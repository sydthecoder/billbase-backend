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
        // Runs daily at 01:00 AM — expires overdue trials and warns orgs expiring in 3 days.
        // Make sure server has this cron entry:
        // * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
        $schedule->command('subscriptions:expire-trials')->dailyAt('01:00');
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