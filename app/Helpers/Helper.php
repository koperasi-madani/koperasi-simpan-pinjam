<?php

namespace App\Helpers;

use App\Models\BukuTabungan;

if (!function_exists('kodeAkunTabungan')) {
    function kodeAkunTabungan($id = null)
    {
       $buku_tabungan = BukuTabungan::whereHas('rekening_tabungan',function($query) use ($id) {
                        $query->where('nasabah_id',$id);
                    })->first()->id_kode_akun ?? null;
        return $buku_tabungan;
    }
}
