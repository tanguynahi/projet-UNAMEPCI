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
        // planigue journaliere
        $schedule->command('cotisation:generer')->dailyAt('14:37');
        // planigue hedbomadaire
        $schedule->command('cotisation:hebdomadaire')->weeklyOn(1, '00:00');
        // planigue mensuelle
        $schedule->command('cotisation:mensuelle')->monthlyOn(1, '00:00');
        // planification annuelle
        $schedule->command('cotisation:annuelle')->yearly();
        // $schedule->command('cotisation:generer')->weeklyOn(1, '09:35') tout les lundis a 09:35
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
