<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function akun(Request $request) {
        $data = KodeAkun::find($request->get('id'));
        return response()->json($data);
    }

    function noRekening(Request $request) {
        // $data = KodeAkun::find($request->get('id'));
        $data = PembukaanRekening::select(
            'rekening_tabungan.id',
            'rekening_tabungan.nasabah_id',
            'rekening_tabungan.no_rekening',
            'rekening_tabungan.saldo_awal',
            'nasabah.id as id_nasabah',
            'nasabah.no_anggota',
            'nasabah.nama'
        )
            ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
            ->where('nasabah.status','aktif')
            ->where('rekening_tabungan.id',$request->get('id'))
            ->first();
        return response()->json($data);
    }
}
