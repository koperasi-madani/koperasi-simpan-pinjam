<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DataAdministrasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = NasabahModel::where('status','aktif')->latest()->get();
        return view('pages.data-administrasi.index',compact('data'));
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
        //
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
        return view('pages.data-administrasi.edit',compact('data'));
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
        DB::beginTransaction();
        try {
            $anggota = NasabahModel::findOrFail($id);
            $anggota->nama = $request->get('nama');
            $anggota->nik = $request->get('nik');
            $anggota->no_hp = $request->get('no_hp');
            $anggota->alamat = $request->get('ket');
            $anggota->pekerjaan = $request->get('pekerjaan');
            $anggota->jenis_kelamin = $request->get('jenis_kelamin');
            $anggota->users_id = Auth::user()->id;
            $anggota->update();
            DB::commit();
            return redirect()->route('perubahan-data-administrasi.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('perubahan-data-administrasi.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            DB::rollBack();
            return redirect()->route('perubahan-data-administrasi.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
