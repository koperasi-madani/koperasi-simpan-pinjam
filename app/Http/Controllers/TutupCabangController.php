<?php

namespace App\Http\Controllers;

use App\Models\TutupBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TutupCabangController extends Controller
{
    public function index() {
        $data = TutupBuku::first();
        return view('pages.tutup-buku.index',compact('data'));
    }
    function post(Request $request) {
        if ($request->has('tutup_cabang')) {
            $tutup = TutupBuku::find(1);
            $tutup->status = 'buka';
            $tutup->id_user = Auth::user()->id;
            $tutup->update();
            Session::put('status_tutup',$tutup->status);
            return redirect()->route('tutup-cabang.index')->withStatus('Berhasil buka cabang');
        }else{
            $tutup = TutupBuku::find(1);
            $tutup->status = 'tutup';
            $tutup->id_user = Auth::user()->id;
            $tutup->update();
            Session::put('status_tutup',$tutup->status);
            return redirect()->route('tutup-cabang.index')->withStatus('Berhasil tutup cabang');
        }
    }
}
