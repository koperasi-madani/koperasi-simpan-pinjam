<?php

namespace App\Http\Controllers;

use App\Models\Denominasi;
use App\Models\SaldoTeller;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DenominasiController extends Controller
{
    public function index() {
        $currentDate = Carbon::now()->toDateString();
        $pembayaran = SaldoTeller::where('status','pembayaran')
                                ->where('tanggal',$currentDate)
                                // ->sum('pembayaran');
                                ->sum('penerimaan');

        $denominasi = Denominasi::where('id_user',auth()->user()->id)->whereDate('created_at','=',$currentDate)->groupBy('id_user')->get();
        return view('pages.informasi-head-teller.informasi-denominasi',compact('pembayaran','denominasi'));
    }

    public function post(Request $request){
        $request->validate([
            'nominal.*' => 'required',
            'jumlah.*' => 'required'
        ]);

        try {
            $currentDate = Carbon::now()->toDateString();
            $current_penerimaan = SaldoTeller::where('status','pembayaran')
                                    ->where('tanggal',$currentDate)
                                    ->sum('penerimaan');
            $denominasi = (int) $request->get('penerimaan_total');
            if ($current_penerimaan != $denominasi) {
                return redirect()->back()->withError('Data tidak sesuai.');
            }
            for ($i=0; $i < count($request->get('nominal')); $i++) {
                $denominasi = new Denominasi;
                $denominasi->id_user = auth()->user()->id;
                $denominasi->nominal = (int) $request->get('nominal')[$i];
                $denominasi->jumlah = (int) $request->get('jumlah')[$i];
                $denominasi->total = (int) $request->get('jumlah')[$i] * (int) $request->get('nominal')[$i];
                $denominasi->save();
            }
            return redirect()->route('informasi.denominasi')->withStatus('Berhasil menambahkan data dengan nominal'.$request->get('penerimaan_total'));
        } catch (Exception $e) {
            return redirect()->back()->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan.');
        }
    }
}
