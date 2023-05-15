<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class InformasiCustomerServiceController extends Controller
{
    public function informasiNasabah()
    {
        $data = NasabahModel::latest()->get();
        return view('pages.informasi-customer-service.nasabah',compact('data'));
    }

    public function informasiRekening()
    {
        $data = PembukaanRekening::with('sukuBunga')->latest()->get();
        return view('pages.informasi-customer-service.rekening',compact('data'));

    }
}
