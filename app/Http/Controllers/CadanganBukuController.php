<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
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
        $data = PembukaanRekening::with('sukuBunga','tabungan')->latest()->get();

        foreach ($data as $item) {
            $sukuBunga = $item->sukuBunga->suku_bunga;
            $persen =  $sukuBunga / 365;
            $total_persen = ($item->tabungan->saldo * round($persen,4)) / 100;
            // 80 persen dari pajak (pph)
            // $result = $hitung * 80 / 100 / 365;
            $result = $total_persen;
            $saldo = new CadanganBuku;
            $saldo->tgl = date(now());
            $saldo->id_nasabah = $item->nasabah_id;
            $saldo->id_rekening = $item->id;
            $saldo->suku_bunga = $sukuBunga;
            $saldo->saldo = $item->tabungan->saldo;
            $saldo->bunga_cadangan = $result;
            $saldo->save();

            $total = CadanganBuku::where('id_rekening',$item->id)->where('id_nasabah',$item->id)->sum('bunga_cadangan');
            PembukaanRekening::where('nasabah_id',$item->nasabah_id)->where('id',$item->id)->update([
                'saldo_bunga' => $total
            ]);
            $kode_akun = KodeAkun::where('kode_akun','42001')->orWhere('kode_akun','21004')->get();
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
                $jurnal->nominal =  $result;
                $jurnal->id_detail = 0;
                $jurnal->save();

            }
        }
    }

    static function totalBunga()
    {
        $data = PembukaanRekening::with('sukuBunga','tabungan')->latest()->get();
        foreach ($data as $item) {
            $total = CadanganBuku::where('id_rekening',$item->id)->where('id_nasabah',$item->nasabah_id)->sum('bunga_cadangan');
            $kode_akun = KodeAkun::where('kode_akun','23001')->orWhere('kode_akun','21004')->get();
            foreach ($kode_akun as $item_akun) {
                $jurnal = new Jurnal;
                $jurnal->tanggal = Carbon::now();
                $jurnal->kode_transaksi = '0';
                $jurnal->keterangan = 'suku bunga';
                $jurnal->kode_akun = $item_akun->id;
                $jurnal->kode_lawan = 0;
                if ($item_akun->kode_akun == '21004') {
                    $jurnal->tipe = 'debit';
                }else{
                    $jurnal->tipe = 'kredit';
                }
                $jurnal->nominal =  $total;
                $jurnal->id_detail = 0;
                $jurnal->save();

            }
            PembukaanRekening::where('id',$item->id)->where('nasabah_id', $item->nasabah_id)->increment('saldo_bunga', $total);
            BukuTabungan::where('id_rekening_tabungan',$item->id)->increment('saldo',$total);
            // Remove this month's entries from CadanganBuku
            CadanganBuku::where('id_nasabah', $item->nasabah_id)
            ->whereMonth('tgl', date('m'))  // this month
            ->whereYear('tgl', date('Y'))   // this year
            ->delete();
        }

    }
}
