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
            $all_dates = \App\Models\Jurnal::select(DB::raw('DATE(created_at) as date'))->distinct()->orderBy('date', 'ASC')->get();
            $laba_rugi_per_day = [];

            // per bulan
            $all_month_years = \App\Models\Jurnal::select(DB::raw('MONTH(created_at) as month, YEAR(created_at) as year'))->distinct()->orderBy('year', 'ASC')->orderBy('month', 'ASC')->get();

            $laba_rugi_per_month = [];
            foreach ($all_dates as $date) {
                $totalPendapatan = 0;
                $totalBeban = 0;

                if (count($kode_pendapatan) > 0) {
                    foreach ($kode_pendapatan as $itemPendapatan) {
                        $sumDebet = \App\Models\Jurnal::whereDate('created_at', $date->date)->where('kode_akun', $itemPendapatan->id)->where('tipe', 'debit')->sum('nominal');
                        $sumKredit = \App\Models\Jurnal::whereDate('created_at', $date->date)->where('kode_akun', $itemPendapatan->id)->where('tipe', 'kredit')->sum('nominal');
                        $totalPendapatan += ($sumKredit - $sumDebet);
                    }
                }

                if (count($kode_modal) > 0) { // Saya asumsikan Anda punya array serupa kode_pendapatan untuk beban
                    foreach ($kode_modal as $itemBeban) {
                        $sumDebet = \App\Models\Jurnal::whereDate('created_at', $date->date)->where('kode_akun', $itemBeban->id)->where('tipe', 'debit')->sum('nominal');
                        $sumKredit = \App\Models\Jurnal::whereDate('created_at', $date->date)->where('kode_akun', $itemBeban->id)->where('tipe', 'kredit')->sum('nominal');
                        $totalBeban += ($sumDebet - $sumKredit);
                    }
                }

                $labaRugiHarian = $totalBeban - $totalPendapatan;
                $laba_rugi_per_day[$date->date] = $labaRugiHarian;
            }

            foreach ($all_month_years as $monthYear) {
                $totalPendapatan = 0;
                $totalBeban = 0;

                if (count($kode_pendapatan) > 0) {
                    foreach ($kode_pendapatan as $itemPendapatan) {
                        $sumDebet = \App\Models\Jurnal::whereMonth('created_at', $monthYear->month)->whereYear('created_at', $monthYear->year)->where('kode_akun', $itemPendapatan->id)->where('tipe', 'debit')->sum('nominal');
                        $sumKredit = \App\Models\Jurnal::whereMonth('created_at', $monthYear->month)->whereYear('created_at', $monthYear->year)->where('kode_akun', $itemPendapatan->id)->where('tipe', 'kredit')->sum('nominal');
                        $totalPendapatan += ($sumKredit - $sumDebet);
                    }
                }

                if (count($kode_modal) > 0) { // Asumsi Anda punya array serupa kode_pendapatan untuk beban
                    foreach ($kode_modal as $itemBeban) {
                        $sumDebet = \App\Models\Jurnal::whereMonth('created_at', $monthYear->month)->whereYear('created_at', $monthYear->year)->where('kode_akun', $itemBeban->id)->where('tipe', 'debit')->sum('nominal');
                        $sumKredit = \App\Models\Jurnal::whereMonth('created_at', $monthYear->month)->whereYear('created_at', $monthYear->year)->where('kode_akun', $itemBeban->id)->where('tipe', 'kredit')->sum('nominal');
                        $totalBeban += ($sumDebet - $sumKredit);
                    }
                }
                $labaRugiBulanan =$totalBeban - $totalPendapatan;
                $laba_rugi_per_month[$monthYear->year . '-' . str_pad($monthYear->month, 2, "0", STR_PAD_LEFT)] = $labaRugiBulanan; // Menyimpan dalam format 'YYYY-MM'
            }
        $this->param['grafik_perbulan'] = $laba_rugi_per_month;
        $this->param['grafik_perhari'] = $laba_rugi_per_day;
        // return $totalSaldoAwalLaba;
        $this->param['tgl'] = Carbon::now()->translatedFormat('d-F-Y');
        return view('dashboard',$this->param);
    }
}
