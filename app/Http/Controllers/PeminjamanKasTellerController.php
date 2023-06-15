<?php

namespace App\Http\Controllers;

use App\Models\PPeminjamanKas;
use App\Models\SaldoTeller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanKasTellerController extends Controller
{
    public function post(Request $request) {
        $currentDate = Carbon::now()->toDateString();
        $current_peminjaman = PPeminjamanKas::where('tanggal',$currentDate)->get();
        if (count($current_peminjaman) > 0) {
            $update_peminjaman = PPeminjamanKas::where('tanggal',$currentDate)->first();
            $update_peminjaman->nominal = $update_peminjaman->nominal + $this->formatNumber($request->get('nominal'));
            $update_peminjaman->update();

            $update_pembayaran = SaldoTeller::where('tanggal',$currentDate)->where('id_user', auth()->user()->id)->first();
            $update_pembayaran->pembayaran = $update_pembayaran->pembayaran + $this->formatNumber($request->get('nominal'));
            $update_pembayaran->penerimaan = $update_pembayaran->penerimaan + $this->formatNumber($request->get('nominal'));
            $update_pembayaran->update();

        }else{
            $pembayaran = new SaldoTeller;
            $pembayaran->kode = $this->generateSaldo();
            $pembayaran->id_user = auth()->user()->id;
            $pembayaran->status = 'pembayaran';
            $pembayaran->pembayaran = $this->formatNumber($request->get('nominal'));
            $pembayaran->penerimaan = $this->formatNumber($request->get('nominal'));
            $pembayaran->tanggal = date('Y-m-d');
            $pembayaran->save();

            $peminjaman = new PPeminjamanKas;
            $peminjaman->kode = $this->generate();
            $peminjaman->kode_akun = $request->get('id_rek');
            $peminjaman->jenis = 'keluar';
            $peminjaman->nominal =  $this->formatNumber($request->get('nominal'));
            $peminjaman->tanggal = date(now());
            $peminjaman->save();
        }
        return redirect()->route('pembayaran.kas-teller')->withStatus('Berhasil menambahkan data');
    }
    public function generate() {
        $nosaldo = null;
        $saldo = PPeminjamanKas::orderBy('created_at', 'DESC')->get();
        $date = date('Ymd');
        if($saldo->count() > 0) {
            $nosaldo = $saldo[0]->kode;

            $lastIncrement = substr($nosaldo, 10);
            $nosaldo = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
            return $nosaldo = 'PS'.$date.$nosaldo;
        }
        else {
            return $nosaldo = 'PS'.$date."001";

        }
    }

    public function generateSaldo()
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
