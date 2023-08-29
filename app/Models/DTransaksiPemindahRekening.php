<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTransaksiPemindahRekening extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi_pemindah_rekening';
    protected $fillable = [
        'kode_transaksi',
        'kode_akun',
        'subtotal',
        'keterangan',
    ];
}
