<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;
    protected $table = 'jurnal';
    protected $fillable = [
        'tanggal',
        'kode_transaksi',
        'keterangan',
        'kode_akun',
        'kode_lawan',
        'tipe',
        'nominal',
        'id_detail',
    ];

    public function kodeAkun() {
        return $this->belongsTo(KodeAkun::class,'kode_akun');
    }
}
