<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\KodeInduk;
use App\Models\KodeLedger;
use App\Models\SaldoTeller;
use App\Models\TransaksiManyToMany;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanNeracaController extends Controller
{
    public function neraca(Request $request){
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
        $KodeAkun = KodeAkun::select('kode_akun.*',
                                'kode_induk.id as kode_induk_id',
                                'kode_induk.kode_induk as nama_kode')
                                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                ->where('kode_akun.nama_akun','NOT LIKE', "%tabungan mudharabah%")
                                ->get();
        return view('pages.laporan.neraca.index',compact('kode_induk','KodeAkun','kode_pendapatan','kode_modal'));
    }

    public function cetak(){

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
        $KodeAkun = KodeAkun::select('kode_akun.*',
                                'kode_induk.id as kode_induk_id',
                                'kode_induk.kode_induk as nama_kode')
                                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                ->where('kode_akun.nama_akun','NOT LIKE', "%tabungan mudharabah%")
                                ->get();
        return view('pages.laporan.neraca.pdf',compact('kode_induk','KodeAkun','kode_pendapatan','kode_modal'));
    }

    function transaksiHarian() {
        $transaksi = TransaksiTabungan::select('transaksi_tabungan.*',
                                'rekening_tabungan.nasabah_id',
                                'rekening_tabungan.no_rekening',
                                'nasabah.id as id_nasabah',
                                'nasabah.nama',
                                'nasabah.nik',
                                'users.id as id_user',
                                'users.kode_user'
                                )->join(
                                    'rekening_tabungan','rekening_tabungan.id','transaksi_tabungan.id_rekening'
                                )->join(
                                    'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                )
                                ->join(
                                    'users', 'users.id', 'transaksi_tabungan.id_user'
                                )
                                ->where('users.id',auth()->user()->id)
                                ->whereDate('transaksi_tabungan.created_at',Carbon::now())
                                ->orderByDesc('transaksi_tabungan.created_at')
                                ->take(10)
                                ->get();
                                $currentDate = Carbon::now()->toDateString();
        $query_pembayaran = SaldoTeller::where('status','pembayaran')
                                        ->where('tanggal',$currentDate);
                                if (Auth::user()->hasRole('head-teller')) {
                                    $pembayaran = $query_pembayaran
                                                    ->where('id_user',auth()->user()->id)
                                                    ->sum('penerimaan');
                                }else{
                                    $pembayaran = $query_pembayaran
                                                    ->where('id_user',auth()->user()->id)
                                                    ->sum('penerimaan');
                                }
        $current_penerimaan = SaldoTeller::where('status', 'pembayaran')
                                ->where('id_user', auth()->user()->id)
                                ->where('tanggal', $currentDate)
                                ->sum('pembayaran');
        return view('pages.laporan.transaksi-harian.index',[
            'transaksi' => $transaksi,
            'pembayaran' => $pembayaran,
            'current_penerimaan' => $current_penerimaan
        ]);
    }

    function transaksiHeadTeller() {
        $currentDate = Carbon::now()->toDateString();

        $pembayaran = SaldoTeller::with('user')
                    ->select('id_user','created_at',DB::raw('SUM(penerimaan) as total_penerimaan'))
                    ->where('status', 'pembayaran')
                    ->whereDate('tanggal', $currentDate)
                    ->groupBy('id_user')
                    ->get();
        return view('pages.laporan.transaksi-head.index',[
            'pembayaran' => $pembayaran
        ]);
    }

    function transaksiMany() {
        // $transaksi_many = Jurnal::where('keterangan','!=','tabungan')->whereDate('created_at',Carbon::now())->get();
        $transaksi_many = TransaksiManyToMany::with('detail','kodeAkun')->whereDate('created_at',Carbon::now())->get();
        $transaksi_many->transform(function($value) {

            if ($value->detail) {
                foreach ($value->detail as $key => $item) {
                    $kode_akun = KodeAkun::where('id',$item->kode_akun)->first()->kode_akun;
                    $item->kode_akun = $kode_akun;
                }
            }
            return $value;
        });
        return view('pages.laporan.transaksi-many.index',[
            'transaksi' => $transaksi_many
        ]);
    }
}
