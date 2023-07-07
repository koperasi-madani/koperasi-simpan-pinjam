<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\KodeLedger;
use App\Models\TransaksiManyToMany;
use Illuminate\Http\Request;

class LaporanNeracaController extends Controller
{
    public function neraca(){
        $ledger = KodeLedger::orderByDesc('id')->get();
        // $induk = KodeAkun::orderByDesc('id')->get();
        // return $induk;
        return view('pages.laporan.neraca.index',compact('ledger'));
    }
}
