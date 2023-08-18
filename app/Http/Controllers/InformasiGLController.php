<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use Illuminate\Http\Request;

class InformasiGLController extends Controller
{
    public function informasiNasabah()
    {
        $nasabah = PembukaanRekening::select('rekening_tabungan.*',
                                    'nasabah.no_anggota',
                                    'nasabah.nik',
                                    'nasabah.nama as nama_nasabah',
                                    'nasabah.alamat',
                                    'nasabah.pekerjaan',
                                    'nasabah.tgl',
                                    'nasabah.status',
                                    'nasabah.jenis_kelamin',
                                    'suku_bunga_koperasi.nama',
                                    'suku_bunga_koperasi.suku_bunga',
                                    'buku_tabungan.id_rekening_tabungan',
                                    'buku_tabungan.tgl_transaksi',
                                    'buku_tabungan.nominal_transaksi',
                                    'buku_tabungan.saldo',
                                    'buku_tabungan.validasi',
                                    'buku_tabungan.jenis')
                                        ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                                        ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga')
                                        ->join('buku_tabungan','buku_tabungan.id_rekening_tabungan','rekening_tabungan.id')
                                        ->get();

        return view('pages.informasi-nasabah.informasi-gl-nasabah.index',compact('nasabah'));
    }

    public function informasiNasabahDetail($id)
    {
        $data = PembukaanRekening::select(
                            'rekening_tabungan.*',
                            'nasabah.no_anggota',
                            'nasabah.nik',
                            'nasabah.nama as nama_nasabah',
                            'nasabah.alamat',
                            'nasabah.pekerjaan',
                            'nasabah.tgl',
                            'nasabah.status',
                            'nasabah.jenis_kelamin',
                            'suku_bunga_koperasi.nama',
                            'suku_bunga_koperasi.suku_bunga',
                            'buku_tabungan.saldo',)
                            ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                            ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga')
                            ->join('buku_tabungan','buku_tabungan.id_rekening_tabungan','rekening_tabungan.id')
                            ->where('rekening_tabungan.nasabah_id',$id)
                            ->first();
        return view('pages.informasi-nasabah.informasi-gl-nasabah.detail',compact('data'));
    }
}
