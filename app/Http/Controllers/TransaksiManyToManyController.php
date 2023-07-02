<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiManyToManyController extends Controller
{
    function index() {
        return view('pages.transaksi-back-office.transaksi-many.index');
    }
}
