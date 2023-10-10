<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\PPeminjamanKas;
use App\Models\SaldoTeller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranKasTellerController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->toDateString();
        $saldo_teller = SaldoTeller::where('tanggal', $currentDate)->pluck('id_user');
        $teller = User::role('teller')->whereNotIn('id',$saldo_teller)->get();
        $kode = $this->generate();
        $kode_akun = KodeAkun::where('nama_akun','like','%Kas%')->get();

        $currentDate = Carbon::now()->toDateString();
        $peminjaman = PPeminjamanKas::where('tanggal',$currentDate)->sum('nominal');
        $item_induk = KodeInduk::select('kode_induk.*','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                ->where('kode_ledger.nama','A K T I V A')
                                ->groupBy('kode_ledger.nama')
                                ->orderBy('kode_induk.kode_induk')
                                ->first();
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
        $saldoAkhir = $this->saldoAkhir($item_induk);

        return view('pages.pembayaran.index',compact('teller','kode','kode_akun','peminjaman','item_induk','saldoAkhir','ledger'));
    }

    public function post(Request $request)
    {

        $request->validate([
            'id_akun' => 'required',
            'kode_pembayaran' => 'required',
            'nominal' => 'required',
        ]);
        try {
            $nominal =  $this->formatNumber($request->get('nominal'));
            $peminjaman = (int) $request->get('peminjaman');
            $result = $peminjaman - $nominal;
            if ($result < 0) {
                return redirect()->back()->withError('saldo tidak mencukupi.');
            }
            // peminjaman
            $currentDate = Carbon::now()->toDateString();
            $peminjaman = PPeminjamanKas::where('tanggal',$currentDate)->first();
            $peminjaman->nominal = $result;
            $peminjaman->update();

            $dataUser = User::role('head-teller')->where('id',auth()->user()->id)->first();

            $current_head_pembayaran = SaldoTeller::where('tanggal',$currentDate)->where('id_user',$dataUser->id)->first();
            $current_head_pembayaran->pembayaran = $result;
            $current_head_pembayaran->penerimaan = $result;
            $current_head_pembayaran->update();

            $current_saldo = SaldoTeller::where('tanggal',$currentDate)->where('id_user',$request->get('id_akun'))->get();

            if (count($current_saldo) > 0) {
                $current_pembayaran = SaldoTeller::where('tanggal',$currentDate)->where('id_user',$request->get('id_akun'))->first();
                $current_pembayaran->kode = $request->get('kode_pembayaran');
                $current_pembayaran->id_user = $request->get('id_akun');
                $current_pembayaran->status = 'pembayaran';
                $current_pembayaran->pembayaran = $current_pembayaran != null ? $current_pembayaran->pembayaran : 0  + $this->formatNumber($request->get('nominal'));
                $current_pembayaran->penerimaan = $current_pembayaran->penerimaan != null ? $current_pembayaran->penerimaan : 0 + $this->formatNumber($request->get('nominal'));
                $current_pembayaran->tanggal = date('Y-m-d');
                $current_pembayaran->update();
            }else{
                $pembayaran = new SaldoTeller;
                $pembayaran->kode = $request->get('kode_pembayaran');
                $pembayaran->id_user = $request->get('id_akun');
                $pembayaran->status = 'pembayaran';
                $pembayaran->pembayaran = $this->formatNumber($request->get('nominal'));
                $pembayaran->penerimaan = $this->formatNumber($request->get('nominal'));
                $pembayaran->tanggal = date('Y-m-d');
                $pembayaran->save();
            }
            return redirect()->route('pembayaran.kas-teller')->withStatus('Berhasil menambahkan data');
        } catch (Exception $e) {
            return redirect()->back()->withError('Terjadi Kesalahan');
        } catch (QueryException $e) {
            return redirect()->back()->withError('Terjadi Kesalahan');
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
