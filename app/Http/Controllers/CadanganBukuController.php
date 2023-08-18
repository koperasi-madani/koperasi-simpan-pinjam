<?php

namespace App\Http\Controllers;

use App\Models\CadanganBuku;
use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\PembukaanRekening;
use App\Models\SukuBunga;
use Carbon\Carbon;
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
            $hitung =  $item->saldo * ( $sukuBunga / 100) ;
            // 80 persen dari pajak (pph)
            // $result = $hitung * 80 / 100 / 365;
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
            $kode_akun = KodeAkun::where('kode_akun','42001')->orWhere('kode_akun','21003')->get();
            foreach ($kode_akun as $item_akun) {
                $jurnal = new Jurnal;
                $jurnal->tanggal = Carbon::now();
                $jurnal->kode_transaksi = '0';
                $jurnal->keterangan = 'suku bunga';
                $jurnal->kode_akun = $item_akun->id;
                $jurnal->kode_lawan = 0;
                if ($item_akun->kode_akun == '42001') {
                    $jurnal->tipe = 'debit';
                }else{
                    $jurnal->tipe = 'kredit';
                }
                $jurnal->nominal =  $total;
                $jurnal->id_detail = 0;
                $jurnal->save();

            }
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
        if (date('d') == '01') {
            foreach ($data as $item) {
                $total = CadanganBuku::where('id_nasabah',$item->id)->sum('bunga_cadangan');
                $kode_akun = KodeAkun::where('kode_akun','21003')->orWhere('kode_akun','22001')->get();
                foreach ($kode_akun as $item_akun) {
                    $jurnal = new Jurnal;
                    $jurnal->tanggal = Carbon::now();
                    $jurnal->kode_transaksi = '0';
                    $jurnal->keterangan = 'suku bunga';
                    $jurnal->kode_akun = $item_akun->id;
                    $jurnal->kode_lawan = 0;
                    if ($item_akun->kode_akun == '21003') {
                        $jurnal->tipe = 'debit';
                    }else{
                        $jurnal->tipe = 'kredit';
                    }
                    $jurnal->nominal =  $total;
                    $jurnal->id_detail = 0;
                    $jurnal->save();

                }
                PembukaanRekening::where('nasabah_id', $item->id)->increment('saldo_bunga', $total);
                // Remove this month's entries from CadanganBuku
                CadanganBuku::where('id_nasabah', $item->id)
                ->whereMonth('tgl', date('m'))  // this month
                ->whereYear('tgl', date('Y'))   // this year
                ->delete();
            }
        }
    }
}
