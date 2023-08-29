<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPemindahRekening extends Model
{
    use HasFactory;
    protected $table = 'transaksi_pemindah_rekening';
    protected $fillable = [
        'kode_transaksi',
        'id_user',
        'tanggal',
        'kode_akun',
        'tipe',
        'total',
        'keterangan',
    ];
}
