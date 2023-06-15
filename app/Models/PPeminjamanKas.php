<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPeminjamanKas extends Model
{
    use HasFactory;
    protected $table = 'p_peminjaman_kas';
    protected $fillable = [
        'kode',
        'kode_akun',
        'jenis',
        'nominal',
        'tanggal',
    ];

}
