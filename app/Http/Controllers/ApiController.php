<?php

namespace App\Http\Controllers;

use App\Models\KodeAkun;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    function akun(Request $request) {
        $data = KodeAkun::find($request->get('id'));
        return response()->json($data);
    }
}
