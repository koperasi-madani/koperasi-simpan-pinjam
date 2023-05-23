<?php

namespace App\Http\Controllers;

use App\Models\CadanganBuku;
use App\Models\PembukaanRekening;
use App\Models\SukuBunga;
use Illuminate\Http\Request;

class CadanganBukuController extends Controller
{
    static function cadangSuku()
    {
        // PembukaanRekening
        // CadanganBuku::;
        $data = PembukaanRekening::select('rekening_tabungan.*',
                                'nasabah.no_anggota',
                                'nasabah.nik',
                                'nasabah.nama',
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
        foreach ($data as $item) {
            $sukuBunga = $item->suku_bunga;
            $hitung =  $item->saldo * $sukuBunga;
            // 80 persen dari pajak (pph)
            $result = $hitung * 80 / 100 / 365;
            $saldo = new CadanganBuku;
            $saldo->tgl = date(now());
            $saldo->id_nasabah = $item->nasabah_id;
            $saldo->suku_bunga = $sukuBunga;
            $saldo->saldo = $item->saldo;
            $saldo->bunga_cadangan = ceil($result);
            $saldo->save();

            $total = CadanganBuku::where('id_nasabah',$item->id)->sum('bunga_cadangan');
            PembukaanRekening::where('nasabah_id',$item->id)->update([
                'saldo_bunga' => $total
            ]);
        }
    }

    static function totalBunga()
    {
        $data = PembukaanRekening::select('rekening_tabungan.*',
                    'nasabah.no_anggota',
                    'nasabah.nik',
                    'nasabah.nama',
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
        foreach ($data as $item) {

            $total = CadanganBuku::where('id_nasabah',$item->id)->sum('bunga_cadangan');
            PembukaanRekening::where('nasabah_id',$item->id)->update([
                'saldo_bunga' => $total
            ]);
        }
    }
}
