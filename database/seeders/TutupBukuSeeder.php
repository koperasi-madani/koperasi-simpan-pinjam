<?php

namespace Database\Seeders;

use App\Models\TutupBuku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutupBukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = new TutupBuku;
        $data->status = 'buka';
        $data->id_user = 4;
        $data->save();
    }
}
