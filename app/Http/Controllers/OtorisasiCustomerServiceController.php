<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\DTransaksiManyToMany;
use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\NasabahModel;
use App\Models\Penarikan;
use App\Models\SaldoTeller;
use App\Models\TransaksiManyToMany;
use App\Models\TransaksiTabungan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class OtorisasiCustomerServiceController extends Controller
{
    public function nasabah()
    {
        $data = NasabahModel::latest()->get();
        return view('pages.otorisasi-customer-service.nasabah',compact('data'));
    }

    public function getNasabah(Request $request)
    {
        $data = NasabahModel::find($request->get('id'));
        return response()->json([
            'data' => $data,
        ]);
    }

    public function postNasabah(Request $request)
    {
        $request->validate([
            'ket_status' => 'required'
        ]);
        try {
            $nasabah = NasabahModel::find($request->get('id'));
            $nasabah->ket_status = $request->get('ket_status');
            $nasabah->status = $request->get('status');
            $nasabah->update();
            return redirect()->route('otorisasi.nasabah')->withStatus('Berhasil mengubah status nasabah : '.$request->get('nama'));
        } catch (Exception $e) {
            return redirect()->route('otorisasi.nasabah')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('otorisasi.nasabah')->withError('Terjadi kesalahan.');
        }
    }

    public function rekening()
    {
        $data  = TransaksiTabungan::select('transaksi_tabungan.*',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'nasabah.id as id_nasabah',
                                    'nasabah.nama',
                                    'nasabah.nik',
                                    'users.id as id_user',
                                    'users.kode_user'
                                    )->join(
                                        'rekening_tabungan','rekening_tabungan.nasabah_id','transaksi_tabungan.id_nasabah'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )
                                    ->join(
                                        'users', 'users.id', 'transaksi_tabungan.id_user'
                                    )
                                    ->where('transaksi_tabungan.jenis','keluar')
                                    ->orderByDesc('transaksi_tabungan.created_at')
                                    ->get();
        return view('pages.otorisasi-customer-service.rekening',compact('data'));
    }

    public function getRekening(Request $request)
    {
        $data = TransaksiTabungan::select('transaksi_tabungan.*',
                'rekening_tabungan.nasabah_id',
                'rekening_tabungan.no_rekening',
                'nasabah.id as id_nasabah',
                'nasabah.nama',
                'nasabah.nik',
                'users.id as id_user',
                'users.kode_user'
                )->join(
                    'rekening_tabungan','rekening_tabungan.nasabah_id','transaksi_tabungan.id_nasabah'
                )->join(
                    'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                )
                ->join(
                    'users', 'users.id', 'transaksi_tabungan.id_user'
                )
                ->where('transaksi_tabungan.jenis','keluar')
                ->where('transaksi_tabungan.id',$request->get('id'))->first();

        return response()->json($data);
    }


    public function postRekening(Request $request)
    {
        $penarikan = TransaksiTabungan::find($request->get('id'));
        if ($request->status == 'setuju') {
            $tabungan = BukuTabungan::select('buku_tabungan.saldo','rekening_tabungan.no_rekening')
                                    ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                    ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'));
            $saldo_akhir = $tabungan->first()->saldo;
            $result_saldo =  $saldo_akhir - $penarikan->nominal;
            // update penerimaan
            $head = User::whereHas(
                'roles', function($q){
                    $q->where('name', 'head-teller');
                }
            )->first();
            $currentDate = Carbon::now()->toDateString();
            $pembayaran = SaldoTeller::where('status','pembayaran')
                ->where('id_user',$head->id)
                ->where('tanggal',$currentDate)
                // ->sum('pembayaran');
                ->first();
            $penerimaan = $pembayaran->penerimaan - $penarikan->nominal;
            $pembayaran->penerimaan = $penerimaan;
            $pembayaran->update();
            $tabungan->update([
                'saldo' => $result_saldo,
            ]);
            $penarikan->saldo = $result_saldo;
            $penarikan->status = 'setuju';

            $kode_akun_tabungan = BukuTabungan::select('buku_tabungan.saldo','rekening_tabungan.no_rekening')
                                                ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                                ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'))->first()->id_kode_akun;

            $kode_akun_kas = KodeAkun::where('nama_akun','Kas Besar')->orWhere('id',$kode_akun_tabungan)->get();
            foreach ($kode_akun_kas as $item) {
                $transaksi = new TransaksiManyToMany();
                $transaksi->kode_transaksi = $this->generateKode();
                $transaksi->id_user = auth()->user()->id;
                $transaksi->tanggal = Date::now();
                $transaksi->kode_akun = $item->id;
                $transaksi->tipe = $item->jenis == 'debit' ? 'kredit' : 'debit';
                $transaksi->total = $penarikan->nominal;
                $transaksi->keterangan = 'Transaksi Many To Many';
                $transaksi->save();

                $detailTransaksi = new DTransaksiManyToMany();
                $detailTransaksi->kode_transaksi = $transaksi->kode_transaksi;
                $detailTransaksi->kode_akun = $item->id;
                $detailTransaksi->subtotal = $penarikan->nominal;
                $detailTransaksi->keterangan = 'tabungan';
                $detailTransaksi->save();

                $jurnal = new Jurnal;
                $jurnal->tanggal = Date::now();
                $jurnal->kode_transaksi = $transaksi->kode_transaksi;
                $jurnal->keterangan = 'tabungan';
                $jurnal->kode_akun = $item->id;
                $jurnal->kode_lawan = 0;
                $jurnal->tipe = $item->jenis == 'debit' ? 'kredit' : 'debit';
                $jurnal->nominal = $penarikan->nominal;
                $jurnal->id_detail = $detailTransaksi->id;
                $jurnal->save();
            }
        }else{
            $penarikan->status = 'ditolak';
        }
        $penarikan->update();
        return redirect()->route('otorisasi.rekening')->withStatus('Berhasil mengubah status penarikan : '.$request->get('nama'));


    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }

     function generateKode() {
        $tanggal = Carbon::now();
        $transaksi = TransaksiManyToMany::whereDate('created_at',$tanggal)->orderBy('created_at', 'DESC')->get();
        $date = date('Ymd');
        if($transaksi->count() > 0) {
            $notransaksi = $transaksi[0]->kode_transaksi;

            $lastIncrement = substr($notransaksi, 10);
            $notransaksi = str_pad($lastIncrement + 1, 3, 0, STR_PAD_LEFT);
            return $notransaksi = 'TM'.$date.$notransaksi;
        }
        else {
            return $notransaksi = 'TM'.$date."001";

        }
    }
}
