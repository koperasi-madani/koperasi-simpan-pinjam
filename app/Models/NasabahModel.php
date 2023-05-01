<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NasabahModel extends Model
{
    use HasFactory;
    protected $table = 'nasabah';
    protected $fillable = [
        'users_id',
        'no_anggota',
        'nik',
        'nama',
        'no_hp',
        'alamat',
        'tgl',
        'sim_pokok',
        'sim_wajib',
        'sim_sukarela',
        'status',
    ];
    public function bukuTabungan()
    {
        return $this->HasOne(PembukaanRekening::class,'nasabah_id');
    }

}
