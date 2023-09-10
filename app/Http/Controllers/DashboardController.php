<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\NasabahModel;
use App\Models\TransaksiTabungan;
use App\Models\TutupBuku;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public $param;
    public function index()
    {
        $this->param['data'] = TransaksiTabungan::latest()->get();
        $this->param['tutupBuku'] = TutupBuku::first();
        $this->param['user'] = User::count();
        $this->param['hak_akses'] = Role::count();
        $this->param['nasabah_aktif'] = NasabahModel::where('status','aktif')->count();
        $this->param['nasabah_non_aktif'] = NasabahModel::where('status','non-aktif')->count();
        $this->param['nasabah'] = NasabahModel::count();
        $kode_pendapatan = KodeAkun::select('kode_akun.id',
                'kode_induk.id as induk_id',
                'kode_induk.kode_induk',
                'kode_induk.nama as nama_induk','kode_induk.jenis',
                'kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->where('kode_ledger.kode_ledger','30000')
                ->get();
        $kode_modal = KodeAkun::select('kode_akun.id',
                'kode_induk.id as induk_id',
                'kode_induk.kode_induk',
                'kode_induk.nama as nama_induk','kode_induk.jenis',
                'kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->where('kode_ledger.kode_ledger','40000')
                ->get();
        $kode_induk = KodeInduk::select('kode_induk.*','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->groupBy('kode_ledger.nama')
                ->orderBy('kode_induk.kode_induk')
                ->get();
                $totalSaldoAwalDebetTotalTiga = 0;
                $totalSaldoAwalKreditTotalTiga = 0;

                $totalMutasiDebetTotal = 0;
                $totalMutasiKreditTotal = 0;

                $totalSaldoAkhirDebetTotalTiga = 0;
                $totalSaldoAkhirKreditTotalTiga = 0;
                $totalAkhir = 0;
        foreach ($kode_induk as $item_induk) {
            $kode_induk_dua = \App\Models\KodeInduk::select('kode_induk.id','kode_induk.id_ledger','kode_induk.kode_induk','kode_induk.nama')->where('id_ledger',$item_induk->id_ledger)->groupBy('kode_induk.kode_induk')->orderBy('id','DESC')->get();
            foreach ($kode_induk_dua as $item_ledger_akun){
                $ledger_data = \App\Models\KodeAkun::select('kode_akun.*',
                                'kode_induk.id as induk_id',
                                'kode_induk.nama as nama_induk','kode_induk.jenis','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                // ->where('kode_induk.id_ledger',$item_induk->id)
                                ->where('kode_akun.id_induk',$item_ledger_akun->id)
                                ->get();
                foreach ($ledger_data as $item_ledger){
                    $mutasiAwalDebet = 0;
                    $mutasiAwalKredit = 0;

                    $mutasiDebet = 0;
                    $mutasiKredit = 0;
                    $current_date = \Carbon\Carbon::now()->format('Y-m-d');
                    if (count($kode_pendapatan) > 0) {
                        if ($item_ledger->nama_induk == 'MODAL' || $item_ledger->nama_akun == 'LABA / RUGI TAHUN BERJALAN') {
                            // Ngitung pendapatan;
                            $mutasiAwalDebetPendapatan = 0;
                            $mutasiAwalKreditPendapatan = 0;

                            $mutasiDebetPendapatan = 0;
                            $mutasiKreditPendapatan = 0;
                            foreach ($kode_pendapatan as $itemPendapatan) {
                                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemPendapatan->id)->count();
                                if ($cekTransaksiAwalDiKode > 0) {
                                    $sumMutasiAwalDebetDiKode = DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                    $sumMutasiAwalKreditDiKode = DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                    if ($itemPendapatan->jenis == 'debit') {
                                        $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                        $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                    }
                                    else{
                                        $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                        $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                    }
                                }

                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemPendapatan->id)->count();

                                if ($cekTransaksiDiKode > 0) {
                                    $sumMutasiDebetDiKode = DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                    $sumMutasiKreditDiKode = DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                    $mutasiDebetPendapatan += $sumMutasiDebetDiKode;
                                    $mutasiKreditPendapatan += $sumMutasiKreditDiKode;

                                }
                                $saldoAwal = $mutasiAwalKreditPendapatan -  $mutasiAwalDebetPendapatan;


                                $saldoAkhir = ($mutasiAwalDebetPendapatan + $mutasiDebetPendapatan) - ($mutasiAwalKreditPendapatan + $mutasiKreditPendapatan);

                                $totalPendapatan = $saldoAwal;
                                $totalMutasiKreditPendapatan =  $mutasiKreditPendapatan - $mutasiDebetPendapatan;
                            }
                            $mutasiAwalDebetModal = 0;
                            $mutasiAwalKreditModal = 0;

                            $mutasiDebetModal = 0;
                            $mutasiKreditModal = 0;
                            foreach ($kode_modal as $key => $itemModal) {
                                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemModal->id)->count();
                                if ($cekTransaksiAwalDiKode > 0) {
                                    $sumMutasiAwalDebetDiKode = DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                    $sumMutasiAwalKreditDiKode = DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                    if ($itemModal->jenis == 'debit') {
                                        $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                        $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                    }
                                    else{
                                        $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                        $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                    }
                                }

                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemModal->id)->count();

                                if ($cekTransaksiDiKode > 0) {
                                    $sumMutasiDebetDiKode = DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                    $sumMutasiKreditDiKode = DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                    $mutasiDebetModal += $sumMutasiDebetDiKode;
                                    $mutasiKreditModal += $sumMutasiKreditDiKode;

                                }
                                $saldoAwal = $mutasiAwalDebetModal - $mutasiAwalKreditModal;

                                $saldoAkhir = ($mutasiAwalDebetModal + $mutasiDebetModal) - ($mutasiAwalKreditModal + $mutasiKreditModal);

                                $totalModal = $saldoAwal;
                                $totalMutasiDebetLaba = $mutasiDebetModal - $mutasiKreditModal;
                            }
                            $totalSaldoAwalLaba =  $totalModal - $totalPendapatan;
                            $totalSaldoAkhirLaba =  $totalSaldoAwalLaba + $totalMutasiKreditPendapatan - $totalMutasiDebetLaba;
                            if ($item_ledger->nama_induk == 'MODAL') {
                                $total = $totalSaldoAwalKreditTotalTiga;
                                $totalAkhir = $totalSaldoAkhirKreditTotalTiga;
                                if ($key === 0) {
                                    $totalSaldoAwalKreditTotalTiga = $total + $totalSaldoAwalLaba;
                                    $totalMutasiDebetTotal = $totalMutasiDebetTotal + $totalMutasiDebetLaba;
                                    $totalMutasiKreditTotal = $totalMutasiKreditTotal + $totalMutasiKreditPendapatan;
                                    $totalSaldoAkhirKreditTotalTiga = ($totalSaldoAwalKreditTotalTiga + $totalMutasiKreditTotal) - $totalMutasiDebetTotal;
                                }

                            }else{
                                $totalSaldoAwalKreditDua =  $totalSaldoAwalLaba;
                                $totalSaldoAkhirKreditDua =  $totalSaldoAkhirLaba;
                                $totalMutasiDebetDua = $totalMutasiDebetLaba;
                                $totalMutasiKreditDua = $totalMutasiKreditPendapatan;
                            }

                        }
                    }
                }
            }
        }
        dd($totalPendapatan);
        $this->param['grafik'] =[$totalPendapatan,$totalModal,$totalSaldoAwalKreditDua];
        $this->param['tgl'] = Carbon::now()->translatedFormat('d-F-Y');
        return view('dashboard',$this->param);
    }
}
