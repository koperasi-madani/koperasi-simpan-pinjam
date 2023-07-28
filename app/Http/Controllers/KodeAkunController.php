<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use App\Models\KodeInduk;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class KodeAkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KodeAkun::select('kode_akun.*',
                    'kode_induk.id as kode_induk_id','kode_induk.jenis as jenis_induk',
                    'kode_induk.kode_induk as nama_kode',
                    'kode_ledger.nama as nama_ledger')
                    ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                    ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                    ->get();
        $kode = KodeInduk::all();
        return view('pages.master-akuntansi.kode-akun.index',compact('data','kode'));
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
            'id_induk' => 'required',
            'jenis' => 'required',
            'nama' => 'required',
            ]);
        try {
            $no = $this->noAkun($request->get('id_induk'));
            $ledger = KodeInduk::select('kode_induk.*','kode_ledger.kode_ledger','kode_ledger.nama')
                    ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                    ->where('kode_induk.id',$request->get('id_induk'))
                    ->first();
            $kode = new KodeAkun;
            $kode->id_induk = $request->get('id_induk');
            $kode->kode_akun = $no;
            $kode->nama_akun = $request->get('nama');

            $kode->jenis = $ledger->nama != 'A K T I V A' && $ledger->nama != 'B I A Y A' ? 'kredit' : 'debit';
            $kode->save();
            return redirect()->route('kode-akun.index')->withStatus('Berhasil menambahkan data.');
        } catch (Exception $e) {
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        }
    }

    public function noAkun($params)
    {
        $ledger = KodeInduk::select('kode_induk.*','kode_ledger.kode_ledger')
                ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                ->where('kode_induk.id',$params)
                ->first();
        $kodeAkun = KodeAkun::where('id_induk',$params)->orderBy('created_at', 'DESC')->get();
        if (count($kodeAkun) > 0) {
            $lastIncrement = substr($kodeAkun[0]->kode_akun, 0);
            $noAkun = str_pad($lastIncrement + 1, 5, 0, STR_PAD_LEFT);
            return $noAkun;
        }else{
            $lastIncrement = substr($ledger->kode_induk, 0);
            $noAkun = str_pad($lastIncrement + 1, 5, 0, STR_PAD_LEFT);
            return $noAkun;

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
        $data = KodeAkun::select('kode_akun.*','kode_induk.kode_induk','kode_induk.nama')
                        ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                        ->where('kode_akun.id',$id)
                        ->first();
        return view('pages.master-akuntansi.kode-akun.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis' => 'required',
            'nama' => 'required',
            ]);
        try {
            $kode = KodeAkun::find($id);
            $kode->nama_akun = $request->get('nama');
            $kode->jenis = $request->get('jenis');
            $kode->update();
            return redirect()->route('kode-akun.index')->withStatus('Berhasil mengganti data.');
        } catch (Exception $e) {
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KodeAkun::find($id)->delete();
            return redirect()->route('kode-akun.index')->withStatus('Berhasil menghapus data.');
        } catch (Exception $e) {
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->route('kode-akun.index')->withError('Terjadi kesalahan.');
        }
    }
}
