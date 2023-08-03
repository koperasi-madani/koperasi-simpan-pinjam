<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\DTransaksiManyToMany;
use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use App\Models\SaldoTeller;
use App\Models\SukuBunga;
use App\Models\TransaksiManyToMany;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembukaanRekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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
                            'suku_bunga_koperasi.suku_bunga')
                            ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                            ->join('suku_bunga_koperasi','suku_bunga_koperasi.id','rekening_tabungan.id_suku_bunga')
                            ->get();
        $kode = KodeAkun::where('nama_akun', 'LIKE', 'tabungan%')->get();
        $sukuBunga = SukuBunga::where('jenis','tabungan')->get();
        $nasabah = NasabahModel::where('status','aktif')->get();
        $date = date('Yd');

        /* generate no anggota  */
        $noAnggota = null;
        $rekening = PembukaanRekening::orderBy('created_at', 'DESC')->get();

        if($rekening->count() > 0) {
            $noRekening = $rekening[0]->no_rekening;

            $lastIncrement = substr($noRekening, 9);
            $noRekening = str_pad($lastIncrement + 1, 7, 0, STR_PAD_LEFT);
            $noRekening = '001'.$noRekening;
            $noRekening;
        }
        else {
            $noRekening = "001"."0000001";

        }
        return view('pages.pembukaan-rekening.index',compact(
            'data',
            'nasabah',
            'noRekening',
            'kode',
            'sukuBunga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $count = PembukaanRekening::count();
        if ($count > 0) {
            $nasabah = PembukaanRekening::where('nasabah_id',$request->get('id_nasabah'))->first();
            $isUniquenasabah = ($nasabah != null) ? $isUniquenasabah = $nasabah->nasabah_id != $request->id_nasabah ? '' : '|unique:rekening_tabungan,nasabah_id' : '' ;
        }else{
            $isUniquenasabah = '';
        }
        $request->validate([
            'id_nasabah' => 'required'.$isUniquenasabah,
            'tgl' => 'required',
            'no_rekening' => 'required',
        ],[
            'unique' => 'Data sudah tersedia.'
        ]);

        try {

            // nambah nasabah
            $nasabah = NasabahModel::find($request->get('id_nasabah'));
            $rekening = new PembukaanRekening;
            $rekening->no_rekening = $request->get('no_rekening');
            $rekening->id_kode_akun = $request->get('kode');
            $rekening->tgl = $nasabah->tgl;
            $rekening->id_suku_bunga = $request->get('suku');
            $rekening->tgl_transaksi = $request->get('tgl');

            $rekening->saldo_awal = $this->formatNumber($request->saldo_awal);
            $rekening->ket = $request->get('ket');
            $rekening->nasabah_id = $request->get('id_nasabah');
            $rekening->save();

            $buku  = new BukuTabungan;
            $buku->id_rekening_tabungan = $rekening->id;
            $buku->id_kode_akun = $request->get('kode');
            $buku->tgl_transaksi = $request->get('tgl');
            $buku->nominal_transaksi = $rekening->saldo_awal;
            $buku->saldo = $rekening->saldo_awal;
            $buku->jenis = 'masuk';
            $buku->save();

             // jurnal;
             $kode_akun = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'))->first()->id_kode_akun;

             $transaksi = new TransaksiManyToMany;
             $transaksi->kode_transaksi = $this->generateKode();
             $transaksi->id_user = auth()->user()->id;
             $transaksi->tanggal = $request->get('tgl');
             $transaksi->kode_akun = $kode_akun;
             $transaksi->tipe = 'debit';
             $transaksi->total = $this->formatNumber($request->saldo_awal);
             $transaksi->keterangan = 'Transaksi Many To Many';
             $transaksi->save();

             $detailTransaksi = new DTransaksiManyToMany;
             $detailTransaksi->kode_transaksi = $transaksi->kode_transaksi;
             $detailTransaksi->kode_akun = $kode_akun;
             $detailTransaksi->subtotal = $this->formatNumber($request->saldo_awal);
             $detailTransaksi->keterangan = 'tabungan';
             $detailTransaksi->save();

             $jurnal = new Jurnal;
             $jurnal->tanggal = $request->get('tgl');
             $jurnal->kode_transaksi = $transaksi->kode_transaksi;
             $jurnal->keterangan = 'tabungan';
             $jurnal->kode_akun = $kode_akun;
             $jurnal->kode_lawan = 0;
             $jurnal->tipe = 'debit';
             $jurnal->nominal =  $this->formatNumber($request->saldo_awal);
             $jurnal->id_detail = $detailTransaksi->id;
             $jurnal->save();


            return redirect()->route('pembukaan-rekening.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return $e;
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return $e;
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        }
    }

    public function cetak($id)
    {
        $data = PembukaanRekening::with('nasabah')->find($id);
        return view('pages.pembukaan-rekening.cetak',compact('data'));
    }

    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = PembukaanRekening::with('nasabah','sukuBunga')->find($id);
        return view('pages.pembukaan-rekening.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            PembukaanRekening::findOrFail($id)->delete();
            return redirect()->route('pembukaan-rekening.index')->withStatus('Berhasil Menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        }
    }

    function generateKode() {
        $nosaldo = null;
        $transaksi = TransaksiManyToMany::orderBy('created_at', 'DESC')->get();
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
