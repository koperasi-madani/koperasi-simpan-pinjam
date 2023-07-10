<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\KodeLedger;
use App\Models\TransaksiManyToMany;
use Illuminate\Http\Request;

class LaporanNeracaController extends Controller
{
    public function neraca(){
        $ledger = KodeLedger::orderByDesc('id')->get();
        $kode_akun = KodeAkun::select('kode_akun.*',
                    'kode_induk.id as induk_id',
                    'kode_induk.nama as nama_induk','kode_induk.jenis')
                    ->join('kode_induk','kode_induk.id','kode_akun.id_induk')->get();
        $totalSaldoAwalDebet = 0;
        $totalSaldoAwalKredit = 0;
        $totalMutasiDebet = 0;
        $totalMutasiKredit = 0;
        $totalSaldoAkhirDebet = 0;
        $totalSaldoAkhirKredit = 0;
        $total = 0;

        $current_saldo_tabungan_result = 0;
        $current_saldo_tabungan_result_saldo_akhir = 0;
        $current_saldo_tabungan_result_saldo_akhir_total = 0;
        $current_date = \Carbon\Carbon::now()->format('Y-m-d');
        foreach ($kode_akun as $item_akun) {
            $mutasiAwalDebet = 0;
            $mutasiAwalKredit = 0;

            $mutasiDebet = 0;
            $mutasiKredit = 0;
            if ($item_akun->nama_induk != 'Tabungan' || $item_akun->nama_induk != 'tabungan') {
                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                if ($cekTransaksiAwalDiKode > 0) {
                    $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('nominal');

                    $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('nominal');

                    if ($item_akun->jenis == 'debit') {
                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                    }
                    else{
                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                    }


                    // cek apakah transaksi sebelumnya juga terdapat di field lawan
                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                    if ($cekTransaksiAwalDiLawan > 0) {
                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                        $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                        $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                    }
                } else {
                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                    if ($cekTransaksiAwalDiLawan > 0) {
                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');
                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                        if ($item_akun->jenis == 'debit') {
                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                        }
                        else{
                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                        }
                    }

                }

                $cekTransaksiDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->whereDate('created_at','>',$current_date)->count();
                if ($cekTransaksiDiKode > 0) {
                    $sumMutasiDebetDiKode = \DB::table('jurnal')->whereDate('created_at','>',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                    $sumMutasiKreditDiKode = \DB::table('jurnal')->whereDate('created_at','>',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                    $mutasiDebet += $sumMutasiDebetDiKode;
                    $mutasiKredit += $sumMutasiKreditDiKode;

                    // cek transaksi di field lawan
                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->count();
                    if ($cekTransaksiDiLawan > 0) {
                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','>',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','>',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                        $mutasiDebet += $sumMutasiDebetDiLawan;
                        $mutasiKredit += $sumMutasiKreditDiLawan;
                    }

                }
                else{ // cek transaksi di field lawan
                    // cek transaksi di field lawan
                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','>=',$current_date)->count();
                    if ($cekTransaksiDiLawan > 0) {
                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                        $mutasiDebet += $sumMutasiDebetDiLawan;
                        $mutasiKredit += $sumMutasiKreditDiLawan;
                    }

                }


                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

                $totalMutasiDebet += $mutasiDebet;
                $totalMutasiKredit += $mutasiKredit;

                if ($item_akun->jenis == 'debit') {
                    $totalSaldoAwalDebet += $saldoAwal;
                    $totalSaldoAkhirDebet += $saldoAkhir;
                }
                else{
                    $totalSaldoAwalKredit += $saldoAwal;
                    $totalSaldoAkhirKredit += $saldoAkhir;
                }
                $total = $totalSaldoAwalDebet + $mutasiDebet - $mutasiKredit;
            }
            // var_dump($totalSaldoAwalKredit);
        }


        return view('pages.laporan.neraca.index',compact('ledger'));
    }
}
