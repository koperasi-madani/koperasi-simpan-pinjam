<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DTransaksiManyToMany extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi_many_to_many';
    protected $fillable = [
        'kode_transaksi',
        'kode_akun',
        'subtotal',
        'keterangan',
    ];
}
