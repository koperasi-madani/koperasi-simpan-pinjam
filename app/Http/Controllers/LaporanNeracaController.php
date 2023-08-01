<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\KodeLedger;
use App\Models\TransaksiManyToMany;
use Illuminate\Http\Request;

class LaporanNeracaController extends Controller
{
    public function neraca(){
        $ledger = KodeAkun::select('kode_akun.*',
                            'kode_induk.id as induk_id',
                            'kode_induk.nama as nama_induk','kode_induk.jenis','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                            ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                            ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                            ->get();
        $kode_induk = KodeInduk::select('kode_induk.*','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                ->groupBy('kode_ledger.nama')
                                ->orderBy('kode_induk.kode_induk')
                                ->get();
        return view('pages.laporan.neraca.index',compact('ledger'));
    }
}
