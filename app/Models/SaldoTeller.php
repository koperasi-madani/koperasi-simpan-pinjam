<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoTeller extends Model
{
    use HasFactory;
    protected $table = 'saldo_teller';
    protected $fillable = [
        'kode',
        'id_user',
        'status',
        'penerimaan',
        'pembayaran',
        'tanggal'
    ];

    function user() {
        return $this->belongsTo(User::class,'id_user','id');
    }
}
