<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\DTransaksiManyToMany;
use App\Models\Jurnal;
use App\Models\KodeAkun;
use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use App\Models\SaldoTeller;
use App\Models\Setoran;
use App\Models\TransaksiManyToMany;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
class SetorTunaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PembukaanRekening::select(
                                    'rekening_tabungan.id',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'rekening_tabungan.saldo_awal',
                                    'nasabah.no_anggota',
                                    'nasabah.nama'
                                )
                                ->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')
                                ->where('nasabah.status','aktif')
                                ->get();

        /* generate no setoran  */
        $noSetoran = null;
        $setoran = TransaksiTabungan::where('jenis','masuk')->orderBy('created_at', 'DESC')->get();

        if($setoran->count() > 0) {
            $noSetoran = $setoran[0]->kode;

            $lastIncrement = substr($noSetoran, 6);

            $noSetoran = str_pad($lastIncrement + 1, 5, 0, STR_PAD_LEFT);
            $noSetoran = 'STR'.$noSetoran;
        }
        else {
            $noSetoran = 'STR'."00001";

        }

        $setoran = TransaksiTabungan::select('transaksi_tabungan.*',
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
                                    ->where('transaksi_tabungan.jenis','masuk')
                                    ->orderByDesc('transaksi_tabungan.created_at')
                                    ->take(10)
                                    ->get();
        return view('pages.setor-tunai.index',compact('data','noSetoran','setoran'));
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
        $request->validate([
            'id_nasabah' => 'required',
            'tgl' => 'required',
            'nominal_setor' => 'required',
        ]);
        try {
            // update penerimaan
            $currentDate = Carbon::now()->toDateString();
            $pembayaran = SaldoTeller::where('status','pembayaran')
                ->where('id_user',auth()->user()->id)
                ->where('tanggal',$currentDate)
                // ->sum('pembayaran');
                ->first();
            if (!isset($pembayaran)) {
                return response()->json([
                    'status' => false,
                    'error' => 'Maaf tidak bisa melakukan setor tunai saldo tidak mencukupi.']);
            }
            $setor = new TransaksiTabungan;
            $setor->id_nasabah = $request->get('id_nasabah');
            $setor->kode = $request->get('kode_setoran');
            $setor->tgl = $request->get('tgl');
            $setor->nominal = $this->formatNumber($request->get('nominal_setor'));
            $setor->ket = $request->get('ket');
            $setor->jenis = 'masuk';
            $setor->status = 'setuju';
            $setor->id_user = Auth::user()->id;


            $cek_setor = TransaksiTabungan::where('id_nasabah',$request->get('id_nasabah'))->get();


            if (count($cek_setor) > 0) {
                $tabungan =BukuTabungan::select('buku_tabungan.*','rekening_tabungan.nasabah_id')
                                ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'));
                $saldo_akhir = $tabungan->first()->saldo;
                $result_saldo = $setor->nominal + $saldo_akhir;

                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
                $penerimaan = $pembayaran->penerimaan + $setor->nominal;
                $pembayaran->penerimaan = $penerimaan;
                $pembayaran->update();
                $setor->saldo = $result_saldo;
            }else{
                $tabungan = BukuTabungan::select('buku_tabungan.*','rekening_tabungan.nasabah_id')
                        ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                        ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'));
                $saldo_awal = $tabungan->first()->saldo;
                $result_saldo = $saldo_awal + $setor->nominal;

                $penerimaan = $pembayaran->penerimaan + $setor->nominal;
                $pembayaran->penerimaan = $penerimaan;
                $pembayaran->update();

                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
                $setor->saldo = $result_saldo;
            }
                // jurnal;
                $kode_akun_tabungan = BukuTabungan::select('buku_tabungan.*','rekening_tabungan.nasabah_id')
                                    ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                                    ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'))->first()->id_kode_akun;

                $kode_akun_kas = KodeAkun::where('nama_akun','Kas Besar')->orWhere('id',$kode_akun_tabungan)->get();

            foreach ($kode_akun_kas as $item) {
                $transaksi = new TransaksiManyToMany();
                $transaksi->kode_transaksi = $this->generateKode();
                $transaksi->id_user = auth()->user()->id;
                $transaksi->tanggal = $request->get('tgl');
                $transaksi->kode_akun = $item->id;
                $transaksi->tipe = $item->jenis;
                $transaksi->total = $this->formatNumber($request->get('nominal_setor'));
                $transaksi->keterangan = 'Transaksi Many To Many';
                $transaksi->save();

                $detailTransaksi = new DTransaksiManyToMany();
                $detailTransaksi->kode_transaksi = $transaksi->kode_transaksi;
                $detailTransaksi->kode_akun = $item->id;
                $detailTransaksi->subtotal = $this->formatNumber($request->get('nominal_setor'));
                $detailTransaksi->keterangan = 'tabungan';
                $detailTransaksi->save();

                $jurnal = new Jurnal;
                $jurnal->tanggal = $request->get('tgl');
                $jurnal->kode_transaksi = $transaksi->kode_transaksi;
                $jurnal->keterangan = 'tabungan';
                $jurnal->kode_akun =$item->id;
                $jurnal->kode_lawan = 0;
                $jurnal->tipe = $item->jenis;
                $jurnal->nominal = $this->formatNumber($request->get('nominal_setor'));
                $jurnal->id_detail = $detailTransaksi->id;
                $jurnal->save();
            }

            $setor->save();
            $no_rekening = PembukaanRekening::where('nasabah_id',$request->get('id_nasabah'))->first()->no_rekening;
            $validasi = Auth::user()->kode_user;
            $transaction =[
                'nominal' => $setor->nominal,
                'tgl' => $setor->tgl,
                'kode' => $setor->kode,
                'no_rekening' => $no_rekening,
                'validasi' => $validasi

            ];
            return redirect()->route('setor-tunai.pdf',['id' => $setor->id])->withStatus('Berhasil menambahkan data.');
            // return response()->route(['transaction' => $transaction]);

        } catch (Exception $e) {
            return $e;
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return $e;
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        }
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = TransaksiTabungan::select('transaksi_tabungan.*',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'nasabah.id as id_nasabah',
                                    'nasabah.nama'
                                    )->join(
                                        'rekening_tabungan','rekening_tabungan.id','transaksi_tabungan.id_nasabah'
                                    )->join(
                                        'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                    )->where('transaksi_tabungan.id',$id)
                                    ->first();
       return view('pages.setor-tunai.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $setor = TransaksiTabungan::find($id);
            $setor->tgl = $request->get('tgl');
            if ($request->get('nominal_setor') != null) {
                $setor->nominal = $this->formatNumber($request->get('nominal_setor'));
                // update saldo tabungan jika ada perubahan data
                $rekening = TransaksiTabungan::find($id);
                $tabungan = BukuTabungan::select('buku_tabungan.*','rekening_tabungan.nasabah_id')
                ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                ->where('rekening_tabungan.nasabah_id',$request->get('id_nasabah'));
                $current_tabungan = $tabungan->first()->saldo;
                $current_saldo = $current_tabungan - $rekening->nominal;
                // update penerimaan
                $currentDate = Carbon::now()->toDateString();
                $pembayaran = SaldoTeller::where('status','pembayaran')
                    ->where('id_user',auth()->user()->id)
                    ->where('tanggal',$currentDate)
                    // ->sum('pembayaran');
                    ->first();
                $penerimaan = $pembayaran->penerimaan != 0 ? $pembayaran->penerimaan - $rekening->nominal : 0 + $rekening->nominal;
                $pembayaran->penerimaan = $penerimaan;
                $pembayaran->update();

                $result_saldo = $current_saldo + $this->formatNumber($request->get('nominal_setor'));
                $penerimaan = $pembayaran->penerimaan != 0 ? $pembayaran->penerimaan +  $this->formatNumber($request->get('nominal_setor')) : 0 + $rekening->nominal;
                $pembayaran->penerimaan = $penerimaan;
                $pembayaran->update();

                $tabungan->update([
                    'saldo' => $result_saldo
                ]);
                $setor->saldo = $result_saldo;
            }
            $setor->ket = $request->get('ket');
            $setor->id_user = Auth::user()->id;
            $setor->update();
            return redirect()->route('setor-tunai.index')->withStatus('Berhasil mengganti data.');

        } catch (Exception $e) {
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $setor = TransaksiTabungan::find($id);
            $rekening = TransaksiTabungan::find($id);
            $tabungan = BukuTabungan::select('buku_tabungan.*','rekening_tabungan.nasabah_id')
                        ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                        ->where('rekening_tabungan.nasabah_id',$setor->id_nasabah);
            $current_tabungan = $tabungan->first()->saldo;
            $current_saldo = $current_tabungan - $rekening->nominal;
            $tabungan->update([
                'saldo' => $current_saldo
            ]);

            $setor->delete();
            return redirect()->route('setor-tunai.index')->withStatus('Berhasil menghapus data.');

        } catch (Exception $e) {
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        }
    }
    public function pdf($id)
    {
        $data = TransaksiTabungan::with('user','nasabah')->find($id);
        $tabungan = BukuTabungan::select('buku_tabungan.saldo','rekening_tabungan.no_rekening')
                    ->join('rekening_tabungan','rekening_tabungan.id','buku_tabungan.id_rekening_tabungan')
                    ->where('rekening_tabungan.nasabah_id',$data->id_nasabah)->first();
        return view('pages.setor-tunai.pdf',compact('data','tabungan'));

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
