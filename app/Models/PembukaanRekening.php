<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembukaanRekening extends Model
{
    use HasFactory;
    protected $table = 'rekening_tabungan';
    protected $fillable = [
        'nasabah_id',
        'id_kode_akun',
        'no_rekening',
        'tgl_transaksi',
        'tgl',
        'saldo_awal',
        'status',
        'ket',
    ];
    public function nasabah()
    {
       return $this->belongsTo(NasabahModel::class,'nasabah_id','id');
    }
}
