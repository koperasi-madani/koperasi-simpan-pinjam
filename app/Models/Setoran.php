<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;
    protected $table = 'setoran';

    public function nasabah()
    {
       return $this->belongsTo(NasabahModel::class,'nasabah_id','id');
    }
}
