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

class PembayaranKasTellerController extends Controller
{
    public function index()
    {
        $teller = User::role('teller')->get();
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

        return view('pages.pembayaran.index',compact('teller','kode','kode_akun','peminjaman','item_induk'));
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
