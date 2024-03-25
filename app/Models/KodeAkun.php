<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeAkun extends Model
{
    use HasFactory;
    protected $table = 'kode_akun';

    public function kodeInduk() {
        return $this->belongsTo(KodeInduk::class,'id_induk');
    }



}

