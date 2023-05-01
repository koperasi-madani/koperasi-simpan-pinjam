<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembukaanRekening extends Model
{
    use HasFactory;
    protected $table = 'buku_tabungan';
    protected $fillable = [
        'nasabah_id',
        'no_rekening',
        'tgl_simpanan',
        'tgl_penarikan',
        'tgl_transaksi',
        'saldo_anggota',
        'jumlah_simpanan',
        'ket',
        'status',
    ];
    public function nasabah()
    {
       return $this->belongsTo(NasabahModel::class,'nasabah_id','id');
    }
}
