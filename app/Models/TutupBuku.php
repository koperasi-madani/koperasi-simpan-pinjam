<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutupBuku extends Model
{
    use HasFactory;
    protected $table = 'tutup_cabang';
    protected $fillable = [
        'status',
        'id_user',
    ];
}
