<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            $noAnggota = $nasabah[0]->no_anggota;

            $lastIncrement = substr($noAnggota, 10);

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
            'nama' => 'required|unique:nasabah,nama',
            'nik' => 'required|unique:nasabah,nik',
            'no_anggota' => 'required',
            'ket' => 'required',
            'nik' => 'required',
            'jenis_kelamin' => 'required',
            'pekerjaan' => 'required',
        ]);
        try {
            $anggota = new NasabahModel;
            $anggota->no_anggota = Str::upper($request->get('no_anggota'));
            $anggota->nama = Str::upper($request->get('nama'));
            $anggota->nik = Str::upper($request->get('nik'));
            $anggota->no_hp = $request->get('no_hp');
            $anggota->alamat = $request->get('ket');
            $anggota->tgl = $request->get('tgl');
            $anggota->pekerjaan = Str::upper($request->get('pekerjaan'));
            $anggota->jenis_kelamin = $request->get('jenis_kelamin');
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
            $anggota->no_anggota = Str::upper($request->get('no_anggota'));
            $anggota->nama = Str::upper($request->get('nama'));
            $anggota->nik = Str::upper($request->get('nik'));
            $anggota->pekerjaan = Str::upper($request->get('pekerjaan'));
            $anggota->no_hp = $request->get('no_hp');
            $anggota->jenis_kelamin = $request->get('jenis_kelamin');
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
