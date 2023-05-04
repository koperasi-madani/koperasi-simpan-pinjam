<?php

namespace App\Http\Controllers;

use App\Models\BukuTabungan;
use App\Models\NasabahModel;
use App\Models\PembukaanRekening;
use App\Models\Setoran;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        )->join('nasabah','nasabah.id','rekening_tabungan.nasabah_id')->get();


        /* generate no anggota  */
        $noAnggota = null;
        $setoran = Setoran::orderBy('created_at', 'DESC')->get();

        if($setoran->count() > 0) {
            $noSetoran = $setoran[0]->kode_setoran;

            $lastIncrement = substr($noSetoran, 6);

            $noSetoran = str_pad($lastIncrement + 1, 4, 0, STR_PAD_LEFT);
            $noSetoran = 'STR'.$noSetoran;
        }
        else {
            $noSetoran = 'STR'."00001";

        }

        $setoran = Setoran::select(
            'setoran.id_rekening_tabungan',
            'setoran.kode_setoran',
            'setoran.tgl_setor',
            'setoran.nominal_setor',
            'setoran.saldo',
            'rekening_tabungan.nasabah_id',
            'rekening_tabungan.no_rekening',
            'nasabah.id',
            'nasabah.nama'
            )->join(
                'rekening_tabungan','rekening_tabungan.id','setoran.id_rekening_tabungan'
            )->join(
                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
            )->get();
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
            $setor = new Setoran;
            $setor->id_rekening_tabungan = $request->get('id_nasabah');
            $setor->kode_setoran = $request->get('kode_setoran');
            $setor->tgl_setor = $request->get('tgl');
            $setor->nominal_setor = $this->formatNumber($request->get('nominal_setor'));
            $setor->validasi = $request->get('ket');
            $setor->id_user = Auth::user()->id;
            $setor->save();

            $cek_setor = Setoran::where('id_rekening_tabungan',$request->get('id_nasabah'))->get();
            if (count($cek_setor) > 0) {
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
                $saldo_akhir = $tabungan->first()->saldo;
                $result_saldo = $setor->nominal_setor + $saldo_akhir;
                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
            }else{
                $tabungan = BukuTabungan::where('id_rekening_tabungan',$request->get('id_nasabah'));
                $saldo_awal = $tabungan->first()->saldo_awal;
                $result_saldo = $saldo_awal + $setor->nominal_setor;
                return $result_saldo;
                $tabungan->update([
                    'saldo' => $result_saldo,
                ]);
            }
            return redirect()->route('setor-tunai.index')->withStatus('Berhasil menambahkan data.');


        } catch (Exception $e) {
            return $e;
            return redirect()->route('setor-tunai.index')->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return $e;
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
        //
    }
}
