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
        'id_suku_bunga',
        'no_rekening',
        'tgl_transaksi',
        'tgl',
        'saldo_awal',
        'status',
        'ket',
        'saldo_bunga',
    ];
    public function nasabah()
    {
       return $this->belongsTo(NasabahModel::class,'nasabah_id','id');
    }

    public function tabungan() {
        return $this->belongsTo(BukuTabungan::class,'id','id_rekening_tabungan');
    }
    public function sukuBunga()
    {
       return $this->belongsTo(SukuBunga::class,'id_suku_bunga','id');
    }

    function  cadangan()  {
        return $this->belongsTo(CadanganBuku::class,'id','id_rekening');
    }
}
