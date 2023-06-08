<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denominasi extends Model
{
    use HasFactory;
    protected $table = 'denominasi';
    protected $fillable = [
        'id_user',
        'nominal',
        'jumlah',
        'total',
    ];
}
