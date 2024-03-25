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
                // ->dailyAt('00:00') // Atur waktu sesuai kebutuhan
                ->everyMinute() // Atur waktu sesuai kebutuhan
                ->appendOutputTo(storage_path('logs/inspire.log'));
       // Jalankan command suku:bulanan pada tanggal terakhir bulan sebelumnya
        $schedule->command('suku:bulanan')
                ->everyMinute()
                // ->monthlyOn(1, '00:00') // Jadwal pada tanggal 1 sebagai cadangan
                // ->when(function () {
                //     // Cek apakah hari saat ini adalah tanggal terakhir bulan
                //     return date('j') == date('t', strtotime('yesterday'));
                // })
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
