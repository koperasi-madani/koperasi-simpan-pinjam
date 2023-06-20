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
        $data_pembayaran = SaldoTeller::select('saldo_teller.*','users.id as id_user', 'users.name')
                                ->join('users','users.id','saldo_teller.id_user')
                                ->where('saldo_teller.status','pembayaran')
                                ->where('saldo_teller.tanggal',$currentDate)
                                ->get();

        $denominasi = Denominasi::where('id_user',auth()->user()->id)->whereDate('created_at','=',$currentDate)->groupBy('id_user')->get();

        $nominal_denominasi = Denominasi::whereDate('denominasi.created_at','=',$currentDate)
                            ->groupBy('denominasi.id_user')
                            ->get()
                            ->map(function ($item) {
                                $item->hasil_perkalian = $item->nominal * (int)$item->jumlah;
                                return $item;
                            });
        return view('pages.informasi-head-teller.informasi-denominasi',compact('pembayaran','denominasi','data_pembayaran','nominal_denominasi'));
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
