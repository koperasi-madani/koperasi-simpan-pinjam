<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\PembukaanRekening;
use App\Models\Penarikan;
use App\Models\Setoran;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PenarikanController extends Controller
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
                'nasabah.id as id_nasabah',
                'nasabah.no_anggota',
                'nasabah.nama'
            )->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')->where('nasabah.status','aktif')->get();


        /* generate no penarikan  */
        $noPenarikan = null;
        $penarikan = Penarikan::orderBy('created_at', 'DESC')->get();

        if($penarikan->count() > 0) {
            $noPenarikan = $penarikan[0]->kode_penarikan;

            $lastIncrement = substr($noPenarikan, 6);

            $noPenarikan = str_pad($lastIncrement + 1, 4, 0, STR_PAD_LEFT);
            $noPenarikan = 'TRK'.$noPenarikan;
        }
        else {
            $noPenarikan = 'TRK'."00001";

        }

        $penarikan = Penarikan::select(
            'penarikan.id',
            'penarikan.id_rekening_tabungan',
            'penarikan.kode_penarikan',
            'penarikan.tgl_setor',
            'penarikan.nominal_setor',
            'penarikan.validasi',
            'penarikan.otorisasi_penarikan',
            'rekening_tabungan.nasabah_id',
            'rekening_tabungan.no_rekening',
            'nasabah.id as id_nasabah',
            'nasabah.nama',
            'nasabah.nik',
            'users.id as id_user',
            'users.kode_user'
            )->join(
                'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
            )->join(
                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
            )
            ->join(
                'users', 'users.id', 'penarikan.id_user'
            )->get();
        return view('pages.penarikan.index',compact('data','noPenarikan','penarikan'));
    }

    public function cekTabungan(Request $request)
    {
        try{
            $data = BukuTabungan::where('id_rekening_tabungan',$request->get('id'))->first()->saldo;
            return $data;

        } catch (Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage()
            ]);
        } catch (QueryException $e){
            return response()->json([
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
        return $request;
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
            'tgl' => 'required',
            'nominal_penarikan' => 'required',
        ]);
        $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
        $saldo_akhir = $tabungan->first()->saldo;
        if ($this->formatNumber($request->get('nominal_penarikan')) > (int)$saldo_akhir) {
            return redirect()->route('penarikan.index')->withError('Maaf saldo anda tidak mencukupi penarikan');
        }
        try {
            $penarikan = new Penarikan;
            $penarikan->kode_penarikan = $request->get('kode_penarikan');
            $penarikan->id_rekening_tabungan = $request->get('id_nasabah');
            $penarikan->tgl_setor = $request->get('tgl');
            $penarikan->nominal_setor = $this->formatNumber($request->get('nominal_penarikan'));
            $penarikan->validasi = $request->get('ket');
            $penarikan->jenis = 'keluar';
            $penarikan->id_user = auth()->user()->id;
            if ($this->formatNumber($request->get('nominal_penarikan')) > 1000000) {
                $penarikan->otorisasi_penarikan = 'pending';
            }else{
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
                $saldo_akhir = $tabungan->first()->saldo;
                $result_saldo =  $saldo_akhir - $penarikan->nominal_setor;
                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
                $penarikan->otorisasi_penarikan = 'setuju';
            }
            $penarikan->save();

            return redirect()->route('penarikan.index')->withStatus('Berhasil melakukan penarikan.');

        } catch (Exception $e) {
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
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
        $data = Penarikan::select(
            'penarikan.id',
            'penarikan.id_rekening_tabungan',
            'penarikan.kode_penarikan',
            'penarikan.tgl_setor',
            'penarikan.nominal_setor',
            'penarikan.validasi',
            'rekening_tabungan.id as rekening_tabungan_id',
            'rekening_tabungan.nasabah_id',
            'rekening_tabungan.no_rekening',
            'nasabah.id as id_nasabah',
            'nasabah.nama',
            'buku_tabungan.id as id_tabungan',
            'buku_tabungan.saldo'
            )->join(
                'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
            )
            ->join(
                'buku_tabungan','buku_tabungan.id_rekening_tabungan','rekening_tabungan.id'
            )
            ->join(
                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
            )
            ->where('penarikan.id',$id)
            ->first();
        return view('pages.penarikan.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl' => 'required',
            'nominal_penarikan' => 'required',
        ]);
        $penarikan = Penarikan::find($id);
        $tabungan = BukuTabungan::where('id_rekening_tabungan',$penarikan->id_rekening_tabungan);
        $saldo_akhir = $tabungan->first()->saldo + $penarikan->nominal_setor;
        if ($this->formatNumber($request->get('nominal_penarikan')) > (int)$saldo_akhir) {
            return redirect()->route('penarikan.index')->withError('Maaf saldo anda tidak mencukupi penarikan');
        }
        try {
            $penarikan = Penarikan::find($id);
            $penarikan->tgl_setor = $request->get('tgl');
            $penarikan->nominal_setor = $this->formatNumber($request->get('nominal_penarikan'));
            $penarikan->validasi = $request->get('ket');
            $penarikan->jenis = 'keluar';
            $penarikan->id_user = auth()->user()->id;
            if ($this->formatNumber($request->get('nominal_penarikan')) > 1000000) {
                $penarikan->otorisasi_penarikan = 'pending';
            }else{
                $tabungan = BukuTabungan::where('id_rekening_tabungan', $penarikan->id_rekening_tabungan);

                $tabungan->update([
                    'saldo' => $this->formatNumber($request->get('sisa_saldo')),
                ]);
                $penarikan->otorisasi_penarikan = 'setuju';
            }
            $penarikan->update();

            return redirect()->route('penarikan.index')->withStatus('Berhasil melakukan perubahan penarikan.');

        } catch (Exception $e) {
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $penarikan = Penarikan::find($id);
            $tabungan = BukuTabungan::where('id_rekening_tabungan', $penarikan->id_rekening_tabungan);
            $saldo_akhir = $tabungan->first()->saldo;
            $result_saldo =  $saldo_akhir + $penarikan->nominal_setor;
            $tabungan->update([
                'saldo' => $result_saldo,
            ]);
            $penarikan->delete();
            return redirect()->route('penarikan.index')->withStatus('Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('penarikan.index')->withError('Terjadi kesalahan.');
        }
    }
}
