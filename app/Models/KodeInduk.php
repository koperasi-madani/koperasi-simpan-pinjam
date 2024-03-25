<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KodeInduk extends Model
{
    use HasFactory;
    protected $table = 'kode_induk';

    public function kodeLedger() {
        return $this->belongsTo(KodeLedger::class,'id_ledger');
    }

}
