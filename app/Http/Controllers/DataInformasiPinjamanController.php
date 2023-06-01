<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class DataInformasiPinjamanController extends Controller
{
    public function index(Request $request)
    {
        return view('tampilan');
    }
}
