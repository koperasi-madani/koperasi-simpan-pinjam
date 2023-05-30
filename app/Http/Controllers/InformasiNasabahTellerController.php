<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\Setoran;
use Illuminate\Http\Request;

class InformasiNasabahTellerController extends Controller
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
                    'nasabah.nik',
                    'users.id as id_user',
                    'users.kode_user'
                    )
                    ->groupBy('rekening_tabungan.nasabah_id')
                    ->join(
                        'rekening_tabungan','rekening_tabungan.id','setoran.id_rekening_tabungan'
                    )->join(
                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                    )
                    ->join(
                        'users', 'users.id', 'setoran.id_user'
                    )
                    ->where(
                        'setoran.id_user',auth()->user()->id
                    )
                    ->get();
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
                                )
                    ->groupBy('rekening_tabungan.nasabah_id')
                    ->join(
                        'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
                    )->join(
                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                    )
                    ->join(
                        'users', 'users.id', 'penarikan.id_user'
                    )
                    ->where(
                        'penarikan.id_user',auth()->user()->id
                    )
                    ->get();

        return view('pages.informasi-nasabah.index',compact('setoran','penarikan'));
    }

    public function informasiNasabahDetail($id)
    {
        $data = PembukaanRekening::select(
                            'rekening_tabungan.*',
                            'nasabah.no_anggota',
                            'nasabah.nik',
                            'nasabah.nama',
                            'nasabah.alamat',
                            'nasabah.pekerjaan',
                            'nasabah.tgl',
                            'nasabah.status',
                            'nasabah.jenis_kelamin',
                            'suku_bunga_koperasi.nama',
                            'suku_bunga_koperasi.suku_bunga')
                            ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                            ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga')
                            ->where('rekening_tabungan.nasabah_id',$id)
                            ->first();
        return view('pages.informasi-nasabah.detail',compact('data'));
    }

    public function detailPenarikan($id)
    {
        $data = PembukaanRekening::select(
                                'rekening_tabungan.*',
                                'nasabah.no_anggota',
                                'nasabah.nik',
                                'nasabah.nama',
                                'nasabah.alamat',
                                'nasabah.pekerjaan',
                                'nasabah.tgl',
                                'nasabah.status',
                                'nasabah.jenis_kelamin',
                                'suku_bunga_koperasi.nama',
                                'suku_bunga_koperasi.suku_bunga')
                                ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                                ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga')
                                ->where('rekening_tabungan.nasabah_id',$id)
                                ->first();
        return view('pages.informasi-nasabah.detail-penarikan',compact('data'));
    }
}
