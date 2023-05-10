<?php

namespace App\Http\Controllers;

use App\Models\KodeLedger;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class KodeLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KodeLedger::all();
        return view('pages.master-akuntansi.kode-ledger.index',compact('data'));
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
            'kode_ledger' => 'required|unique:kode_ledger,kode_ledger',
            'nama' => 'required'
        ]);
        try {
            $kode = new KodeLedger;
            $kode->kode_ledger = $request->get('kode_ledger');
            $kode->nama = $request->get('nama');
            $kode->save();
            return redirect()->route('kode-ledger.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');
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
        $data = KodeLedger::find($id);
        return view('pages.master-akuntansi.kode-ledger.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kode_ledger = KodeLedger::where('kode_ledger',$request->get('kode_ledger'))->first()->kode_ledger;
        $cek = $kode_ledger == $request->get('kode_ledger') ? '' : '|unique:kode_ledger,kode_ledger';
        $request->validate([
            'kode_ledger' => 'required'.$cek,
            'nama' => 'required'
        ]);
        try {
            $kode = KodeLedger::find($id);
            $kode->kode_ledger = $request->get('kode_ledger');
            $kode->nama = $request->get('nama');
            $kode->update();
            return redirect()->route('kode-ledger.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e) {
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KodeLedger::find($id)->delete();
            return redirect()->route('kode-ledger.index')->withStatus('Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-ledger.index')->withError('Terjadi kesalahan.');

        }

    }
}
