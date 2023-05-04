<?php

namespace Database\Seeders;

use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\KodeLedger;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KodeRekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kodeL = ['20000'];
        $namaL = ['PASSIVA'];
        $kodeI = ['22000'];
        $namaI = ['Tabungan'];
        $kodeA = ['22001'];
        $namaA = ['TABUNGAN MUDHARABAH'];
        for ($i=0; $i < count($kodeL); $i++) {
            $l = new KodeLedger;
            $l->kode_ledger = $kodeL[$i];
            $l->nama = $namaL[$i];
            $l->save();

            $induk = new KodeInduk;
            $induk->id_ledger = $l->id;
            $induk->kode_induk = $kodeI[$i];
            $induk->nama = $namaI[$i];
            $induk->save();

            $a = new KodeAkun;
            $a->id_induk = $induk->id;
            $a->kode_akun = $kodeA[$i];
            $a->nama_akun = $namaA[$i];
            $a->save();
        }
    }
}
