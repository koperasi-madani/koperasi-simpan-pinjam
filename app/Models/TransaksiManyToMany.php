<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiManyToMany extends Model
{
    use HasFactory;
    protected $table = 'transaksi_many_to_many';
    protected $fillable = [
        'kode_transaksi',
        'id_user',
        'tanggal',
        'kode_akun',
        'tipe',
        'total',
        'keterangan',
    ];

    function detail() {
        return $this->hasMany(DTransaksiManyToMany::class,'kode_transaksi','kode_transaksi');
    }

    function kodeAkun() {
        return $this->belongsTo(KodeAkun::class,'kode_akun');
    }
}
