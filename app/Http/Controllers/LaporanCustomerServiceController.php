<?php

namespace App\Http\Controllers;

use App\Models\PembukaanRekening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LaporanCustomerServiceController extends Controller
{
    public function laporanBukaRekening(Request $request)
    {
        Session::forget('dari');
        Session::forget('sampai');
        if ($request->has('dari') || $request->has('sampai')) {
            Session::put('dari',$request->get('dari'));
            Session::put('sampai',$request->get('sampai'));
        }
        $query = PembukaanRekening::select('rekening_tabungan.*',
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
                                'buku_tabungan.id_rekening_tabungan','buku_tabungan.saldo')
                                ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                                ->join('buku_tabungan','buku_tabungan.id_rekening_tabungan','rekening_tabungan.id')
                                ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga');
        if ($request->has('dari') && $request->has('sampai')) {
            $data = $query->whereBetween('rekening_tabungan.tgl',[$request->get('dari'),$request->get('sampai')])->orderByDesc('rekening_tabungan.created_at')->get();
        }else{
            $data = $query->orderBy('rekening_tabungan.created_at','DESC')->get();
        }
        return view('pages.laporan.laporan-pembukaan-rekening.index',compact('data'));
    }

    public function laporanBukaRekeningPdf(Request $request)
    {
        $query = PembukaanRekening::select('rekening_tabungan.*',
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
                                'buku_tabungan.id_rekening_tabungan','buku_tabungan.saldo')
                                ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                                ->join('buku_tabungan','buku_tabungan.id_rekening_tabungan','rekening_tabungan.id')
                                ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga');
        if (Session::has('dari') || Session::has('sampai')) {
            $data = $query->whereBetween('rekening_tabungan.tgl',[Session::get('dari'),Session::get('sampai')])->get();
        }else{
            $data = $query->get();
        }
        return view('pages.laporan.laporan-pembukaan-rekening.pdf',compact('data'));

    }
}
