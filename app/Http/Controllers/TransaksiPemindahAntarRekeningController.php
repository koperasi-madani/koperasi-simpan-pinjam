<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\DTransaksiPemindahRekening;
use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\PembukaanRekening;
use App\Models\TransaksiPemindahRekening;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiPemindahAntarRekeningController extends Controller
{
    function index() {
        $data = TransaksiPemindahRekening::select('transaksi_pemindah_rekening.*','kode_akun.kode_akun')
                ->join('kode_akun','kode_akun.id','transaksi_pemindah_rekening.kode_akun')
                ->orderByDesc('created_at')
                ->get();
        return view('pages.transaksi-back-office.transaksi-pemindah.index',compact('data'));
    }

    function create() {
        $KodeAkun = KodeAkun::select('kode_akun.*',
                'kode_induk.id as kode_induk_id',
                'kode_induk.kode_induk as nama_kode')
                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                ->where('kode_akun.nama_akun','NOT LIKE', "%tabungan mudharabah%")
                ->get();
        $kodeRekening = PembukaanRekening::select(
                'rekening_tabungan.id',
                'rekening_tabungan.nasabah_id',
                'rekening_tabungan.no_rekening',
                'rekening_tabungan.saldo_awal',
                'nasabah.id as id_nasabah',
                'nasabah.no_anggota',
                'nasabah.nama'
            )->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')->where('nasabah.status','aktif')->get();
        $kode = $this->generateKode();
        return view('pages.transaksi-back-office.transaksi-pemindah.create',compact('KodeAkun','kode','kodeRekening'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'akun_lawan.*' => 'required',
            'nominal.*' => 'required',
            'ket.*' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($_POST['nominal'] as $key => $value) {
                $total += $this->formatNumber($_POST['nominal'][$key]);
            }
            $transaksi = new TransaksiPemindahRekening;
            $transaksi->kode_transaksi = $this->generateKode();
            $transaksi->id_user = auth()->user()->id;
            $transaksi->tanggal = Carbon::now();
            $transaksi->kode_akun = $request->get('kode_akun')[0];
            $transaksi->tipe = $request->get('tipe_akun')[0];
            $transaksi->total = $total;
            $transaksi->keterangan = 'Transaksi Pemindah Rekening';
            $transaksi->save();

            // detail transaksi many to many
            $detailTransaksi = new DTransaksiPemindahRekening;
            $detailTransaksi->kode_transaksi = $transaksi->kode_transaksi;
            $detailTransaksi->kode_akun = $_POST['kode_akun'][0];
            // $detailTransaksi->subtotal = (string) $this->formatNumber($_POST['nominal'][$key]);
            $detailTransaksi->subtotal =  $this->formatNumber($request->get('nominal_akun')[0]) + $total;
            $detailTransaksi->keterangan = $request->get('ket_akun');
            $detailTransaksi->save();

            // kode akun dibikin jurnal dan kode rekening dibikin jurnal masuk ke
            // untuk tabungan
            foreach ($_POST['nominal'] as $key => $value) {
                $setor = new TransaksiTabungan;
                $setor->id_nasabah = $_POST['akun_nasabah'][$key];
                if ($_POST['tipe'][$key] == 'Masuk') {
                    $setor->kode = $this->generateTransaksiPenarikan();
                }else{
                    $setor->kode = $this->generateTransaksiSetoran();
                }
                $setor->tgl = Carbon::now();
                $setor->nominal = $this->formatNumber($_POST['nominal'][$key]);
                $setor->ket =  $_POST['ket'][$key];
                $setor->jenis = $_POST['tipe'][$key] == 'Masuk' ? 'keluar' : 'masuk';
                $setor->status = $_POST['tipe'][$key];
                $setor->id_user = Auth::user()->id;
                if ($_POST['tipe'][$key] == 'Masuk') {
                    $tabungan = BukuTabungan::select('buku_tabungan.saldo','rekening_tabungan.no_rekening')
                                            ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                            ->where('rekening_tabungan.nasabah_id',$_POST['akun_nasabah'][$key]);
                    $saldo_akhir = $tabungan->first()->saldo;
                    $result_saldo =  $saldo_akhir - $this->formatNumber($_POST['nominal'][$key]);
                    $tabungan->update([
                        'saldo' => $result_saldo,
                        ]);
                }else{
                    $tabungan = BukuTabungan::select('buku_tabungan.saldo','rekening_tabungan.no_rekening')
                                            ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                            ->where('rekening_tabungan.nasabah_id',$_POST['akun_nasabah'][$key]);
                    $saldo_akhir = $tabungan->first()->saldo;
                    $result_saldo = $this->formatNumber($_POST['nominal'][$key]) + $saldo_akhir;

                    $tabungan->update([
                        'saldo' => $result_saldo,
                    ]);
                    $setor->saldo = $result_saldo;
                }
                $setor->save();

                // jurnal
                $kode_akun = KodeAkun::where('kode_akun','23001')->first();
                $jurnal = new Jurnal;
                $jurnal->tanggal = Carbon::now();
                $jurnal->kode_transaksi = $transaksi->kode_transaksi;
                $jurnal->keterangan = $_POST['ket'][$key];
                $jurnal->kode_akun = $_POST['kode_akun'][0];
                $jurnal->kode_lawan = 0;
                $jurnal->tipe = $_POST['tipe'][$key] == 'Masuk' ? 'kredit' : 'debit';
                $jurnal->nominal = $this->formatNumber($_POST['nominal'][$key]);
                $jurnal->id_detail = $detailTransaksi->id;
                $jurnal->save();
            }
            // untuk kode akun
            foreach ($_POST['nominal_akun'] as $key => $value) {
                $jurnal = new Jurnal;
                $jurnal->tanggal = Carbon::now();
                $jurnal->kode_transaksi = $transaksi->kode_transaksi;
                $jurnal->keterangan = $_POST['ket'][$key];
                $jurnal->kode_akun = $kode_akun->id;
                $jurnal->kode_lawan = 0;
                $jurnal->tipe = $_POST['tipe'][$key] == 'Masuk' ? 'debit' : 'kredit';
                $jurnal->nominal =  $this->formatNumber($_POST['nominal_akun'][$key]);
                $jurnal->id_detail = $detailTransaksi->id;
                $jurnal->save();
            }

            DB::commit();
            return redirect()->route('transaksi.pemindah.index')->withStatus('Berhasil menambahkan data transaksi');

        } catch (Exception $e) {
            return $e;
            DB::rollBack();
            return redirect()->route('transaksi.pemindah.index')->withError('Terjadi Kesalahan');
        } catch (QueryException $e) {
            return $e;
            DB::rollBack();
            return redirect()->back()->withError('Terjadi kesalahan.');
       }
    }

    function generateKode() {
        $nosaldo = null;
        $transaksi = TransaksiPemindahRekening::orderBy('created_at', 'DESC')->get();
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

    public function formatNumber($param)
    {
        $cleaned = str_replace('.', '', $param);
        return (int)$cleaned;
    }

    function generateTransaksiPenarikan() {
        $tanggal = Carbon::now();
        $penarikan = TransaksiTabungan::whereDate('created_at',$tanggal)->orderBy('created_at', 'DESC')->where('jenis','keluar')->get();
        $date = Carbon::now()->format('dmy');
        if($penarikan->count() > 0) {
            // Mengambil bagian kode yang merepresentasikan nomor urutan
            $lastIncrement = (int) substr($penarikan[0]->kode, -5);

            // Menaikkan nomor urutan
            $nextIncrement = $lastIncrement + 1;

            // Memastikan nomor urutan selalu memiliki 5 digit
            $formattedIncrement = str_pad($nextIncrement, 5, "0", STR_PAD_LEFT);
        }
        else {
            $formattedIncrement = "00001";

        }
        return 'TTF' . $date . $formattedIncrement;

    }
    function generateTransaksiSetoran() {
        $tanggal = Carbon::now();
        $date = Carbon::now()->format('dmy');
        $setoran = TransaksiTabungan::whereDate('created_at',$tanggal)->where('jenis','masuk')->orderBy('created_at', 'DESC')->get();
        if($setoran->count() > 0) {
            // Mengambil bagian kode yang merepresentasikan nomor urutan
            $lastIncrement = (int) substr($setoran[0]->kode, -5);

            // Menaikkan nomor urutan
            $nextIncrement = $lastIncrement + 1;

            // Memastikan nomor urutan selalu memiliki 5 digit
            $formattedIncrement = str_pad($nextIncrement, 5, "0", STR_PAD_LEFT);
        }
        else {
            $formattedIncrement = "00001";

        }
        return 'STF' . $date . $formattedIncrement;

    }
}
