<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\Setoran;
use App\Models\TransaksiTabungan;
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
                                        'rekening_tabungan','rekening_tabungan.nasabah_id','transaksi_tabungan.id_nasabah'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )
                                    ->join(
                                        'users', 'users.id', 'transaksi_tabungan.id_user'
                                    )
                                    ->where('transaksi_tabungan.jenis','masuk')
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
                                        'rekening_tabungan','rekening_tabungan.nasabah_id','transaksi_tabungan.id_nasabah'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )
                                    ->join(
                                        'users', 'users.id', 'transaksi_tabungan.id_user'
                                    )
                                    ->where('transaksi_tabungan.jenis','keluar')
                                    ->get();
        return view('pages.informasi-head-teller.informasi-nasabah',compact('setoran','penarikan'));
    }
}
