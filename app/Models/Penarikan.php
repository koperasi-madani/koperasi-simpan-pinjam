<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory;
    protected $table = 'penarikan';
    protected $fillable = [
        'nasabah_id',
        'id_user',
        'id_rekening_tabungan',
        'kode_penarikan',
        'tgl_setor',
        'nominal_setor',
        'validasi',
        'jenis',
        'otorisasi_penarikan',
    ];
}
