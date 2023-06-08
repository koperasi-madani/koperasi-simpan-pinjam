<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use App\Models\SaldoTeller;
use App\Models\Setoran;
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

            // update penerimaan
            $currentDate = Carbon::now()->toDateString();
            $pembayaran = SaldoTeller::where('status','pembayaran')
                ->where('id_user',auth()->user()->id)
                ->where('tanggal',$currentDate)
                // ->sum('pembayaran');
                ->first();
            if (count($cek_setor) > 0) {
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
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
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
                $saldo_awal = $tabungan->first()->saldo_awal;
                $result_saldo = $saldo_awal + $setor->nominal;

                $penerimaan = $pembayaran->penerimaan + $setor->nominal;
                $pembayaran->penerimaan = $penerimaan;
                $pembayaran->update();

                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
                $setor->saldo = $result_saldo;
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
            // return redirect()->route('setor-tunai.index')->withStatus('Berhasil menambahkan data.');
            return response()->json(['transaction' => $transaction]);

        } catch (Exception $e) {
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
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
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$rekening->id_nasabah);
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
            $tabungan = BukuTabungan::where('id_rekening_tabungan',$rekening->id_nasabah);
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
    public function pdf(Request $request)
    {
        $transaction = $request->input('transaction');
        // Buat tampilan HTML untuk transaksi setor tunai menggunakan Blade Template
        $html = view('pages.setor-tunai.pdf', compact('transaction'))->render();

        $pdf = PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        $filename = $transaction['kode'].'.'.'pdf';
        $file_path = public_path('pdf/setor/') .$filename;
        $pdf->save($file_path);

        return response()->json(['file_path' => asset('pdf/setor/'.$filename)]);

    }
}
