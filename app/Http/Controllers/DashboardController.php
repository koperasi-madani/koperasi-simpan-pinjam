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

        $jurnals = \App\Models\Jurnal::with('kodeAkun.kodeInduk.kodeLedger')->get();

        $laba_rugi_per_day = [];
        $laba_rugi_per_month = [];

        foreach ($jurnals as $jurnal) {
            // Ambil tanggal dari jurnal
            $date = $jurnal->created_at->format('Y-m-d');

            // Inisialisasi total pendapatan dan beban
            $totalPendapatan = 0;
            $totalBeban = 0;

            // Cek apakah kode adalah pendapatan atau modal
            if ($jurnal->kodeAkun->kodeInduk->kodeLedger->kode_ledger === '30000') {
                // Pendapatan
                $totalPendapatan += ($jurnal->tipe === 'kredit') ? $jurnal->nominal : -$jurnal->nominal;
            } elseif ($jurnal->kodeAkun->kodeInduk->kodeLedger->kode_ledger === '40000') {
                // Modal
                $totalBeban += ($jurnal->tipe === 'debit') ? $jurnal->nominal : -$jurnal->nominal;
            }

            // Simpan data per hari
            if (!isset($laba_rugi_per_day[$date])) {
                $laba_rugi_per_day[$date] = 0;
            }
            $laba_rugi_per_day[$date] += $totalBeban - $totalPendapatan;

            // Simpan data per bulan
            $monthYear = $jurnal->created_at->format('Y-m');
            if (!isset($laba_rugi_per_month[$monthYear])) {
                $laba_rugi_per_month[$monthYear] = 0;
            }
            $laba_rugi_per_month[$monthYear] += $totalBeban - $totalPendapatan;
        }

        $this->param['grafik_perbulan'] = $laba_rugi_per_month;
        $this->param['grafik_perhari'] = $laba_rugi_per_day;
        // return $totalSaldoAwalLaba;
        $this->param['tgl'] = Carbon::now()->translatedFormat('d-F-Y');
        return view('dashboard',$this->param);
    }
}
