<?php

namespace App\Http\Controllers;

use App\Models\NasabahModel;
use App\Models\TransaksiTabungan;
use App\Models\TutupBuku;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public $param;
    public function index()
    {
        $this->param['data'] = TransaksiTabungan::latest()->get();
        $this->param['tutupBuku'] = TutupBuku::first();
        $this->param['user'] = User::count();
        $this->param['hak_akses'] = Role::count();
        $this->param['nasabah_aktif'] = NasabahModel::where('status','aktif')->count();
        $this->param['nasabah_non_aktif'] = NasabahModel::where('status','non-aktif')->count();
        $this->param['nasabah'] = NasabahModel::count();
        return view('dashboard',$this->param);
    }
}
