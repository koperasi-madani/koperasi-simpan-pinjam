<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiTabungan extends Model
{
    use HasFactory;
    protected $table = "transaksi_tabungan";
    protected $fillable = [
        'id_nasabah',
        'id_user',
        'kode',
        'nominal',
        'jenis',
        'status',
        'tgl',
        'saldo',
        'ket',
    ];

    function user() {
        return $this->belongsTo(User::class,'id_user');
    }
    function nasabah() {
        return $this->belongsTo(NasabahModel::class,'id_nasabah');
    }

}
