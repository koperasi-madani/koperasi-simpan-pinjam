<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NasabahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = NasabahModel::all();
        return view('pages.nasabah.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $date = date('Ymd');

        /* generate no anggota  */
        $noAnggota = null;
        $nasabah = NasabahModel::orderBy('created_at', 'DESC')->get();

        if($nasabah->count() > 0) {
            $noAnggota = $nasabah[0]->kode_no_anggota;

            $lastIncrement = substr($noAnggota, 11);

            $noAnggota = str_pad($lastIncrement + 1, 4, 0, STR_PAD_LEFT);
            $noAnggota = "AG".$date.$noAnggota;
        }
        else {
            $noAnggota = "AG".$date."0001";
        }
        return view('pages.nasabah.create',compact('noAnggota'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_anggota' => 'required',
            'ket' => 'required',
            'nik' => 'required',
        ]);
        try {
            $anggota = new NasabahModel;
            $anggota->no_anggota = $request->get('no_anggota');
            $anggota->nama = $request->get('nama');
            $anggota->nik = $request->get('nik');
            $anggota->no_hp = $request->get('no_hp');
            $anggota->alamat = $request->get('ket');
            $anggota->tgl = $request->get('tgl');
            $anggota->sim_pokok = $this->formatNumber($request->get('simpanan_pokok'));
            $anggota->sim_wajib = $this->formatNumber($request->get('simpanan_wajib'));
            $anggota->sim_sukarela = $this->formatNumber($request->get('simpanan_sukarela'));
            $anggota->users_id = Auth::user()->id;
            $anggota->save();
            return redirect()->route('nasabah.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
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
        $data = NasabahModel::find($id);
        return view('pages.nasabah.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'no_anggota' => 'required',
            'ket' => 'required',
        ]);
        try {
            $anggota = NasabahModel::findOrFail($id);
            $anggota->nama = $request->get('nama');
            $anggota->nik = $request->get('nik');
            $anggota->no_hp = $request->get('no_hp');
            $anggota->alamat = $request->get('ket');
            $anggota->sim_pokok = $this->formatNumber($request->get('simpanan_pokok'));
            $anggota->sim_wajib = $this->formatNumber($request->get('simpanan_wajib'));
            $anggota->sim_sukarela = $this->formatNumber($request->get('simpanan_sukarela'));
            $anggota->users_id = Auth::user()->id;
            $anggota->update();
            return redirect()->route('nasabah.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            NasabahModel::findOrFail($id)->delete();
            return redirect()->route('nasabah.index')->withStatus('Berhasil Menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('nasabah.index')->withError('Terjadi kesalahan.');
        }
    }
}
