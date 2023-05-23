<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class InformasiAdminKreditController extends Controller
{
    public function informasiNasabah()
    {
        $data = NasabahModel::latest()->get();
        return view('pages.informasi-pinjaman.nasabah',compact('data'));
    }

    public function informasiRekening()
    {
        $data = PembukaanRekening::with('sukuBunga')->latest()->get();
        return view('pages.informasi-pinjaman.rekening',compact('data'));
    }
}
