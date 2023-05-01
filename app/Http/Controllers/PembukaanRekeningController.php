<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
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
        $data = PembukaanRekening::with('nasabah')->get();
        $nasabah = NasabahModel::all();
        $date = date('Yd');

        /* generate no anggota  */
        $noAnggota = null;
        $rekening = PembukaanRekening::orderBy('created_at', 'DESC')->get();

        if($rekening->count() > 0) {
            $noRekening = $rekening[0]->no_rekening;

            $lastIncrement = substr($noRekening, 6);

            $noRekening = str_pad($lastIncrement + 1, 4, 0, STR_PAD_LEFT);
            $noRekening = $date.$noRekening;
        }
        else {
            $noRekening = $date."0001";

        }
        return view('pages.pembukaan-rekening.index',compact('data','nasabah','noRekening'));
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
            $isUniquenasabah = ($nasabah != null) ? $isUniquenasabah = $nasabah->nasabah_id != $request->id_nasabah ? '' : '|unique:buku_tabungan,nasabah_id' : '' ;
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
            $nasabah = NasabahModel::find($request->get('id_nasabah'));
            $simpanan = NasabahModel::select(DB::raw('SUM(sim_pokok+sim_wajib+sim_sukarela) as amount'))->where('id',$request->get('id_nasabah'))->first();
            $buku = new PembukaanRekening;
            $buku->no_rekening = $request->get('no_rekening');
            $buku->tgl_simpanan = $nasabah->tgl;
            $buku->tgl_transaksi = $request->get('tgl');
            $buku->jumlah_simpanan = $simpanan->amount;
            $buku->ket = $request->get('ket');
            $buku->nasabah_id = $request->get('id_nasabah');
            $buku->save();
            return redirect()->route('pembukaan-rekening.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('pembukaan-rekening.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = PembukaanRekening::with('nasabah')->find($id);
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
}
