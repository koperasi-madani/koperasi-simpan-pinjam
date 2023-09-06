<?php

namespace Database\Seeders;

use App\Models\Jurnal;
use App\Models\KodeAkun;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaldowAwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kode_akun = KodeAkun::get();
        $yesterday = now()->subDay();
        foreach ($kode_akun as $item) {
            $jurnal = new Jurnal();
            $jurnal->tanggal = $yesterday;
            $jurnal->kode_transaksi = 0;
            $jurnal->keterangan = 'saldo awal';
            $jurnal->kode_akun = $item->id;
            $jurnal->kode_lawan = 0;
            $jurnal->tipe = 'debit';
            $jurnal->nominal = 1000000;
            $jurnal->created_at = $yesterday;
            $jurnal->save();
        }
    }
}
