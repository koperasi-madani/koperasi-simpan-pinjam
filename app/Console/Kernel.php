<?php

namespace App\Console;

use App\Console\Commands\CadanganBukuSuku;
use App\Console\Commands\CadanganTotalBukuSuku;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        CadanganBukuSuku::class,
        CadanganTotalBukuSuku::class,
    ];
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('suku:cron')
                 ->everyMinute()
                 ->appendOutputTo(storage_path('logs/inspire.log'));
        $schedule->command('totalsuku:cron')
                 ->monthly()
                 ->appendOutputTo(storage_path('logs/inspire.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
