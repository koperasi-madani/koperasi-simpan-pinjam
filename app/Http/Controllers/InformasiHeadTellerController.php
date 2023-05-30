<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\Setoran;
use Illuminate\Http\Request;

class InformasiHeadTellerController extends Controller
{
    public function informasiNasabah()
    {
        $setoran = Setoran::select(
                            'setoran.id',
                            'setoran.id_rekening_tabungan',
                            'setoran.kode_setoran',
                            'setoran.tgl_setor',
                            'setoran.nominal_setor',
                            'setoran.validasi',
                            'setoran.saldo',
                            'rekening_tabungan.nasabah_id',
                            'rekening_tabungan.no_rekening',
                            'nasabah.id as id_nasabah',
                            'nasabah.nama',
                            'users.id as id_user',
                            'users.kode_user'
                            )->join(
                                'rekening_tabungan','rekening_tabungan.id','setoran.id_rekening_tabungan'
                            )->join(
                                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                            )
                            ->join(
                                'users', 'users.id', 'setoran.id_user'
                            )->get();
        $penarikan = Penarikan::select(
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
        return view('pages.informasi-head-teller.informasi-nasabah',compact('setoran','penarikan'));
    }
}
