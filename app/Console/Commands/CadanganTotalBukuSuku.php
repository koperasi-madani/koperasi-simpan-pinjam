<?php

namespace App\Console\Commands;

use App\Http\Controllers\CadanganBukuController;
use Illuminate\Console\Command;

class CadanganTotalBukuSuku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suku:bulanan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cadangkan = new CadanganBukuController();
        $cadangkan->totalBunga();

        $this->info('Suku bunga total successfully!');
    }
}
