<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadanganBuku extends Model
{
    use HasFactory;
    protected $table = 'cadangan_bunga';
    protected $fillable = [
        'tgl',
        'id_nasabah',
        'suku_bunga',
        'saldo',
        'bunga_cadangan',
    ];
}
