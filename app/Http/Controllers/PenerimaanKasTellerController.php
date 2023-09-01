<?php

namespace App\Http\Controllers;

use App\Models\Denominasi;
use App\Models\SaldoTeller;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerimaanKasTellerController extends Controller
{
    public function index()
    {

        $currentDate = Carbon::now()->toDateString();
        $query_pembayaran = SaldoTeller::where('status','pembayaran')
                                        ->where('tanggal',$currentDate);
                                if (Auth::user()->hasRole('head-teller')) {
                                    $pembayaran = $query_pembayaran
                                                    ->sum('penerimaan');
                                }else{
                                    $pembayaran = $query_pembayaran
                                                    ->where('id_user',auth()->user()->id)
                                                    ->sum('penerimaan');
                                }
        $penerimaan = SaldoTeller::where('penerimaan','!=',0)
                    ->where('id_user',auth()->user()->id)
                    ->where('tanggal',$currentDate)
                    // ->sum('pembayaran');
                    ->count();

        $denominasi = Denominasi::where('id_user',auth()->user()->id)->whereDate('created_at','=',$currentDate)->groupBy('id_user')->get();
        $query = Denominasi::where('id_user',auth()->user()->id);
                            if (Auth::user()->hasRole('head-teller')) {
                              $nominal_denominasi = $query
                                                    ->where('status_akun','general')
                                                    ->whereDate('created_at','=',$currentDate)
                                                    // ->where('status_akun','non-general')
                                                    ->sum('total');
                            }
                            $nominal_denominasi = $query
                                                    ->whereDate('created_at','=',$currentDate)
                                                    ->sum('total');

        return view('pages.penerimaan.index',compact('pembayaran','penerimaan','denominasi','nominal_denominasi'));
    }

    public function post(Request $request)
    {

        $request->validate([
            'nominal.*' => 'required',
            'jumlah.*' => 'required'
            ]);

        try {
            $currentDate = Carbon::now()->toDateString();
            // Fetch the current penerimaan. If no record is found, default to 0.
            $current_penerimaan_record = SaldoTeller::where('status', 'pembayaran')
                                            ->where('id_user', auth()->user()->id)
                                            ->where('tanggal', $currentDate)
                                            ->first();

            $current_penerimaan = $current_penerimaan_record ? $current_penerimaan_record->penerimaan : 0;

            $nominal_denominasi = Denominasi::where('id_user', auth()->user()->id)
                                        ->whereDate('created_at', $currentDate)
                                        ->sum('total');

            if ($nominal_denominasi > 0) {
                $current_penerimaan -= $nominal_denominasi;
            }
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
                $denominasi->status_akun = 'non-general';
                $denominasi->save();
            }
            return redirect()->route('penerimaan.kas-teller')->withStatus('Berhasil menambahkan data dengan nominal = '.$request->get('penerimaan_total'));
        } catch (Exception $e) {
            return redirect()->back()->withError('Terjadi kesalahan.');
        } catch (QueryException $e){
            return redirect()->back()->withError('Terjadi kesalahan.');
        }
    }
    public function formatNumber($param)
    {
        return (int)str_replace('.', '', $param);
    }
}
