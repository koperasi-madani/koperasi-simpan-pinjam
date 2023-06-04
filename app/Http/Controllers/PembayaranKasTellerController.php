<?php

namespace App\Http\Controllers;

use App\Models\SaldoTeller;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PembayaranKasTellerController extends Controller
{
    public function index()
    {
        $teller = User::role('teller')->get();
        $kode = $this->generate();
        return view('pages.pembayaran.index',compact('teller','kode'));
    }

    public function post(Request $request)
    {
        $request->validate([
            'id_akun' => 'required',
            'kode_pembayaran' => 'required',
            'nominal' => 'required',
        ]);
        try {
            $pembayaran = new SaldoTeller;
            $pembayaran->kode = $request->get('kode_pembayaran');
            $pembayaran->id_user = $request->get('id_akun');
            $pembayaran->status = 'pembayaran';
            $pembayaran->pembayaran = $this->formatNumber($request->get('nominal'));
            $pembayaran->tanggal = date('Y-m-d');
            $pembayaran->save();
            return redirect()->route('pembayaran.kas-teller')->withStatus('Berhasil menambahkan data');
        } catch (Exception $e) {
            return redirect()->back()->withError('Terjadi Kesalahan');
        } catch (QueryException $e) {
            return redirect()->back()->withError('Terjadi Kesalahan');
        }

    }

    public function generate()
    {
        $nosaldo = null;
        $saldo = SaldoTeller::orderBy('created_at', 'DESC')->get();
        $date = date('Ymd');
        if($saldo->count() > 0) {
            $nosaldo = $saldo[0]->kode;

            $lastIncrement = substr($nosaldo, 10);
            $nosaldo = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
            return $nosaldo = 'ST'.$date.$nosaldo;
        }
        else {
            return $nosaldo = 'ST'.$date."001";

        }
    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
