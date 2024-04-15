<?php

namespace App\Http\Controllers;

use App\Models\KodeInduk;
use App\Models\PPeminjamanKas;
use App\Models\SaldoTeller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanKasTellerController extends Controller
{
    public function post(Request $request) {
         $item_induk = KodeInduk::with('kodeLedger')
                        ->whereHas('kodeLedger', function($query) {
                            $query->where('nama', 'A K T I V A');
                        })
                        ->orderBy('kode_induk')
                        ->first();
        // $item_induk = KodeInduk::select('kode_induk.*','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
        // ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
        // ->where('kode_ledger.nama','A K T I V A')
        // ->groupBy('kode_ledger.nama')
        // ->orderBy('kode_induk.kode_induk')
        // ->first();
        if ($this->formatNumber($request->get('nominal')) != $this->saldoAkhir($item_induk)) {
            return redirect()->route('pembayaran.kas-teller')->withError('Nominal tidak sesuai.');
        }
        $currentDate = Carbon::now()->toDateString();
        $current_peminjaman = PPeminjamanKas::where('tanggal',$currentDate)->get();
        if (count($current_peminjaman) > 0) {
            return redirect()->route('pembayaran.kas-teller')->withError('Transaksi hanya dapat dilakukan sekali.');
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
        $tanggalSekarang = Carbon::now();
        $saldo = PPeminjamanKas::whereDate('created_at',$tanggalSekarang)->orderBy('created_at', 'DESC')->get();
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
        $tanggalSekarang = Carbon::now();
        $saldo = SaldoTeller::whereDate('created_at',$tanggalSekarang)->orderBy('created_at', 'DESC')->get();
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

    public function saldoAkhir($item_induk) {
        $totalSaldoAwalDebet = 0;
        $totalSaldoAwalKredit = 0;
        $totalMutasiDebet = 0;
        $totalMutasiKredit = 0;
        $totalSaldoAkhirDebet = 0;
        $totalSaldoAkhirKredit = 0;

        $ledger = \App\Models\KodeAkun::select('kode_akun.*',
                        'kode_induk.id as induk_id',
                        'kode_induk.kode_induk',
                        'kode_induk.nama as nama_induk','kode_induk.jenis',
                        'kode_ledger.id as ledger_id',
                        'kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                        ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                        ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                        // ->where('kode_induk.id_ledger',$item_induk->id)
                        ->where('kode_akun.kode_akun','11001')
                        ->where('kode_akun.id_induk',$item_induk->id)
                        ->first();
        $mutasiAwalDebet = 0;
        $mutasiAwalKredit = 0;

        $mutasiDebet = 0;
        $mutasiKredit = 0;
        $current_date = \Carbon\Carbon::now()->format('Y-m-d');

        // cek apakah ada jurnal awal di field kode
        $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $ledger->id)->count();

        if ($cekTransaksiAwalDiKode > 0) {
            $sumMutasiAwalDebetDiKode = DB::table('jurnal')->where('kode_akun', $ledger->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

            $sumMutasiAwalKreditDiKode = DB::table('jurnal')->where('kode_akun', $ledger->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

            if ($ledger->jenis == 'debit') {
                $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
            }
            else{
                $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
            }
        }

        // cek transaksi di field kode
        $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $ledger->id)->count();

        if ($cekTransaksiDiKode > 0) {
            $sumMutasiDebetDiKode = DB::table('jurnal')->where('kode_akun', $ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

            $sumMutasiKreditDiKode = DB::table('jurnal')->where('kode_akun', $ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

            $mutasiDebet += $sumMutasiDebetDiKode;
            $mutasiKredit += $sumMutasiKreditDiKode;

        }
        $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;


        $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

        $totalMutasiDebet += $mutasiDebet;
        $totalMutasiKredit += $mutasiKredit;

        if ($ledger->jenis == 'debit') {
            $totalSaldoAwalDebet += $saldoAwal;
            $totalSaldoAkhirDebet += $saldoAkhir;
        }
        else{
            $totalSaldoAwalKredit += $saldoAwal;
            $totalSaldoAkhirKredit += $saldoAkhir;
        }
        return $saldoAkhir;
    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
