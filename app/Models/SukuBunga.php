<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SukuBunga extends Model
{
    use HasFactory;
    protected $table = 'suku_bunga_koperasi';
    protected $fillable = [
        'id_akun',
        'nama',
        'suku_bunga',
        'keterangan',
    ];
}
