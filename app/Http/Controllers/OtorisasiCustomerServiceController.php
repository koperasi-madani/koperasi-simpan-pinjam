<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OtorisasiCustomerServiceController extends Controller
{
    public function nasabah()
    {
        $data = NasabahModel::latest()->get();
        return view('pages.otorisasi-customer-service.nasabah',compact('data'));
    }

    public function getNasabah(Request $request)
    {
        $data = NasabahModel::find($request->get('id'));
        return response()->json([
            'data' => $data,
        ]);
    }

    public function postNasabah(Request $request)
    {
        $request->validate([
            'ket_status' => 'required'
        ]);
        try {
            $nasabah = NasabahModel::find($request->get('id'));
            $nasabah->ket_status = $request->get('ket_status');
            $nasabah->status = $request->get('status');
            $nasabah->update();
            return redirect()->route('otorisasi.nasabah')->withStatus('Berhasil mengubah status nasabah : '.$request->get('nama'));
        } catch (Exception $e) {
            return redirect()->route('otorisasi.nasabah')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('otorisasi.nasabah')->withError('Terjadi kesalahan.');
        }
    }

    public function rekening()
    {
        return view('pages.otorisasi-customer-service.rekening');
    }
}
