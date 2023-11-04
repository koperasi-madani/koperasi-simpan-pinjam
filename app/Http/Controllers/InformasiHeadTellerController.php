<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\SaldoTeller;
use App\Models\Setoran;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InformasiHeadTellerController extends Controller
{
    public function informasiNasabah()
    {
        $setoran = TransaksiTabungan::select('transaksi_tabungan.*',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'nasabah.id as id_nasabah',
                                    'nasabah.nama',
                                    'nasabah.nik',
                                    'users.id as id_user',
                                    'users.kode_user'
                                    )->join(
                                        'rekening_tabungan','rekening_tabungan.id','transaksi_tabungan.id_rekening'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )
                                    ->join(
                                        'users', 'users.id', 'transaksi_tabungan.id_user'
                                    )
                                    ->where('transaksi_tabungan.jenis','masuk')
                                    ->orderByDesc('transaksi_tabungan.created_at')
                                    ->get();
        $penarikan = TransaksiTabungan::select('transaksi_tabungan.*',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'nasabah.id as id_nasabah',
                                    'nasabah.nama',
                                    'nasabah.nik',
                                    'users.id as id_user',
                                    'users.kode_user'
                                    )->join(
                                        'rekening_tabungan','rekening_tabungan.id','transaksi_tabungan.id_rekening'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )
                                    ->join(
                                        'users', 'users.id', 'transaksi_tabungan.id_user'
                                    )
                                    ->where('transaksi_tabungan.jenis','keluar')
                                    ->orderByDesc('transaksi_tabungan.created_at')
                                    ->get();
        return view('pages.informasi-head-teller.informasi-nasabah',compact('setoran','penarikan'));
    }

    public function informasiSemuaSaldo()
    {
        $currentDate = Carbon::now()->toDateString();

        $pembayaran = SaldoTeller::where('status','pembayaran')->where('tanggal',$currentDate)->sum('pembayaran');
        $penerimaan = SaldoTeller::where('tanggal',$currentDate)->sum('penerimaan');

        $data_pembayaran = SaldoTeller::select('saldo_teller.*','users.name')
                                            ->join('users','users.id','saldo_teller.id_user')
                                            ->orderByDesc('saldo_teller.created_at')
                                            ->get();
        return view('pages.informasi-head-teller.informasi-semua-saldo-teller',
            compact('penerimaan','pembayaran','data_pembayaran'));

    }
    public function informasiSaldoTeller()
    {
        $data_pembayaran = SaldoTeller::select('saldo_teller.*','users.name')
                            ->join('users','users.id','saldo_teller.id_user')
                            ->orderBy('saldo_teller.created_at','DESC')
                            ->get();
        return view('pages.informasi-head-teller.informasi-saldo-teller',
                            compact('data_pembayaran'));
    }
}
