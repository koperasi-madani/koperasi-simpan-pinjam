<?php

namespace App\Http\Controllers;

use App\Models\KodeInduk;
use App\Models\KodeLedger;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class KodeIndukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kode = KodeLedger::all();
        $data = KodeInduk::select('kode_induk.*',
                'kode_ledger.id as ledger_id',
                'kode_ledger.kode_ledger',
                'kode_ledger.nama as nama_ledger')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->get();
        return view('pages.master-akuntansi.kode-induk.index',compact('data','kode'));
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
        $ledger = KodeInduk::select('kode_induk.*','kode_ledger.kode_ledger')->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')->where('id_ledger',$request->get('id_ledger'))->where('kode_induk.kode_induk',$request->get('kode_induk'))->orderBy('id')->first();
        $cek = '';
        if ($ledger != null) {
            $cek = ($ledger->kode_induk == $request->get('kode_induk')) ? '|unique:kode_induk,kode_induk' : '' ;
        }
        $request->validate([
            'id_ledger' => 'required',
            'kode_induk' => 'required'.$cek,
            'nama' => 'required',
        ]);
        try {
            $kode = new KodeInduk;
            $kode->id_ledger = $request->get('id_ledger');
            $kode->kode_induk = $request->get('kode_induk');
            $kode->nama = $request->get('nama');
            $kode->jenis = $request->get('jenis');
            $kode->save();
            return redirect()->route('kode-induk.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');
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
        $data = KodeInduk::find($id);
        $kode = KodeLedger::all();
        return view('pages.master-akuntansi.kode-induk.edit',compact('data','kode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ledger = KodeInduk::select('kode_induk.*','kode_ledger.kode_ledger')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->where('id_ledger',$request->get('id_ledger'))
                ->where('kode_induk.id',$id)
                ->first();
        $cek = '';
        if ($ledger != null) {
            $cek = ($ledger->kode_induk == $request->get('kode_induk')) ? '' : '|unique:kode_induk,kode_induk';
        }
        $request->validate([
            'id_ledger' => 'required',
            'kode_induk' => 'required'.$cek,
            'nama' => 'required',
        ]);
        try {
            $kode = KodeInduk::find($id);
            $kode->id_ledger = $request->get('id_ledger');
            $kode->kode_induk = $request->get('kode_induk');
            $kode->nama = $request->get('nama');
            $kode->jenis = $request->get('jenis');
            $kode->update();
            return redirect()->route('kode-induk.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KodeInduk::find($id)->delete();
            return redirect()->route('kode-induk.index')->withStatus('Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-induk.index')->withError('Terjadi kesalahan.');

        }
    }
}
