<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\NasabahModel;
use App\Models\Penarikan;
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
        $data = Penarikan::select(
                                'penarikan.id',
                                'penarikan.id_rekening_tabungan',
                                'penarikan.kode_penarikan',
                                'penarikan.tgl_setor',
                                'penarikan.nominal_setor',
                                'penarikan.validasi',
                                'penarikan.otorisasi_penarikan',
                                'rekening_tabungan.nasabah_id',
                                'rekening_tabungan.no_rekening',
                                'nasabah.id as id_nasabah',
                                'nasabah.nama',
                                'nasabah.nik',
                                'users.id as id_user',
                                'users.kode_user'
                                )->join(
                                    'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
                                )->join(
                                    'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                )
                                ->join(
                                    'users', 'users.id', 'penarikan.id_user'
                                )->get();

        return view('pages.otorisasi-customer-service.rekening',compact('data'));
    }

    public function getRekening(Request $request)
    {
        $data = Penarikan::select(
                        'penarikan.id',
                        'penarikan.id_rekening_tabungan',
                        'penarikan.kode_penarikan',
                        'penarikan.tgl_setor',
                        'penarikan.nominal_setor',
                        'penarikan.validasi',
                        'penarikan.otorisasi_penarikan',
                        'rekening_tabungan.nasabah_id',
                        'rekening_tabungan.no_rekening',
                        'nasabah.id as id_nasabah',
                        'nasabah.nama',
                        'nasabah.nik',
                        'users.id as id_user',
                        'users.kode_user'
                        )->join(
                            'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
                        )->join(
                            'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                        )
                        ->join(
                            'users', 'users.id', 'penarikan.id_user'
                        )->where('penarikan.id',$request->get('id'))->first();

        return response()->json([
            'data' => $data,
        ]);
    }


    public function postRekening(Request $request)
    {
        $penarikan = Penarikan::find($request->get('id'));
        if ($request->status == 'setuju') {
            $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
            $saldo_akhir = $tabungan->first()->saldo;
            $result_saldo =  $saldo_akhir - $this->formatNumber($penarikan->total_penarikan);
            $tabungan->update([
                'saldo' => $result_saldo,
            ]);
            $penarikan->otorisasi_penarikan = 'setuju';
        }else{
            $penarikan->otorisasi_penarikan = 'ditolak';
        }
        $penarikan->update();
        return redirect()->route('otorisasi.rekening')->withStatus('Berhasil mengubah status penarikan : '.$request->get('nama'));


    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
