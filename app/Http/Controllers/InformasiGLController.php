<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class InformasiGLController extends Controller
{
    public function informasiNasabah()
    {
        $nasabah = PembukaanRekening::with('sukuBunga','tabungan','nasabah')->latest()->get();

        return view('pages.informasi-nasabah.informasi-gl-nasabah.index',compact('nasabah'));
    }

    public function informasiNasabahDetail($id)
    {
        $data = PembukaanRekening::with('sukuBunga','tabungan','nasabah','cadangan')->where('id',$id)->first();
        return view('pages.informasi-nasabah.informasi-gl-nasabah.detail',compact('data'));
    }
}
