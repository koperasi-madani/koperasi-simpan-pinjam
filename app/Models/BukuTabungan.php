<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuTabungan extends Model
{
    use HasFactory;
    protected $table = 'buku_tabungan';
    protected $fillable = [
        'id_rekening_tabungan',
        'tgl_transaksi',
        'nominal_transaksi',
        'saldo',
        'validasi',
        'jenis',
        'id_kode_akun',
    ];

    function rekening_tabungan() {
        return $this->belongsTo(PembukaanRekening::class,'id_rekening_tabungan');
    }
}
