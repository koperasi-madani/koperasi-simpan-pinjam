<?php

namespace App\Http\Controllers;

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
        $currentTime = Carbon::now();
        $hour = $currentTime->hour;
        // Batasi akses ke form hanya pada pagi (06:00 - 11:59) dan sore (12:00 - 17:59)
        $pembayaran = null;
        if ($hour >= 6 && $hour <= 17) {
            $currentDate = Carbon::now()->toDateString();
            $pembayaran = SaldoTeller::where('status','pembayaran')
                                    ->where('id_user',auth()->user()->id)
                                    ->where('tanggal',$currentDate)
                                    // ->sum('pembayaran');
                                    ->first();
            $penerimaan = SaldoTeller::where('penerimaan','!=',0)
                        ->where('id_user',auth()->user()->id)
                        ->where('tanggal',$currentDate)
                        // ->sum('pembayaran');
                        ->count();
            return view('pages.penerimaan.index',compact('pembayaran','penerimaan'));
        } else {
            return view('pages.penerimaan.index',compact('pembayaran'));
        }
    }

    public function post(Request $request)
    {
        $request->validate([
            'nominal' => 'required'
        ]);

        try {
            $currentDate = Carbon::now()->toDateString();
            SaldoTeller::where('status','pembayaran')
                                ->where('id_user',auth()->user()->id)
                                ->where('tanggal',$currentDate)
                                ->where('id',$request->get('id_saldo'))
                                ->update([
                                    'penerimaan' => $this->formatNumber($request->get('nominal'))
                                ]);
            return redirect()->route('pembayaran.kas-teller')->withStatus('Berhasil menambahkan data dengan nominal'.$request->get('nominal'));
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
