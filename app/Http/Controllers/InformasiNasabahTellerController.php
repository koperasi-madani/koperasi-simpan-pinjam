<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\Setoran;
use App\Models\TransaksiTabungan;
use Illuminate\Http\Request;

class InformasiNasabahTellerController extends Controller
{
    public function informasiNasabah()
    {
        $nasabah = PembukaanRekening::with('sukuBunga','tabungan','nasabah')->latest()->get();
        return view('pages.informasi-nasabah.index',compact('nasabah'));
    }

    public function informasiNasabahDetail($id)
    {
        $data = PembukaanRekening::with('sukuBunga','tabungan','nasabah','cadangan')->where('id',$id)->first();
        return view('pages.informasi-nasabah.detail',compact('data'));
    }

    public function detailPenarikan($id)
    {
        $data = PembukaanRekening::with('nasabah','sukuBunga','tabungan')
                                ->where('rekening_tabungan.nasabah_id',$id)
                                ->first();
        return view('pages.informasi-nasabah.detail-penarikan',compact('data'));
    }
}
