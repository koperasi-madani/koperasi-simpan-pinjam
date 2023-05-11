<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\SukuBunga;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SukuBungaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SukuBunga::all();
        $kode = KodeAkun::all();
        return view('pages.suku-bunga.index',compact('data','kode'));
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
            'id_akun' => 'required',
            'nama' => 'required',
            'keterangan' => 'required',
            'jenis' => 'required',
        ]);
        try{
            $suku = new SukuBunga;
            $suku->id_akun = $request->get('id_akun');
            $suku->nama = $request->get('nama');
            $suku->suku_bunga = $request->get('suku');
            $suku->keterangan = $request->get('keterangan');
            $suku->jenis = $request->get('jenis');
            $suku->save();
            return redirect()->route('suku-bunga-koperasi.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        }
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
        $data = SukuBunga::find($id);
        $kode = KodeAkun::all();
        return view('pages.suku-bunga.edit',compact('data','kode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_akun' => 'required',
            'nama' => 'required',
            'keterangan' => 'required',
            'jenis' => 'required',
        ]);
        try{
            $suku = SukuBunga::find($id);
            $suku->id_akun = $request->get('id_akun');
            $suku->nama = $request->get('nama');
            $suku->suku_bunga = $request->get('suku');
            $suku->keterangan = $request->get('keterangan');
            $suku->jenis = $request->get('jenis');
            $suku->update();
            return redirect()->route('suku-bunga-koperasi.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            SukuBunga::find($id)->delete();
            return redirect()->route('suku-bunga-koperasi.index')->withStatus('Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('suku-bunga-koperasi.index')->withError('Terjadi kesalahan.');
        }
    }
}
