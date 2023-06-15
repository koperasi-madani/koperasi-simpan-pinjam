<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('p_peminjaman_kas', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->bigInteger('kode_akun');
            $table->enum('jenis',['masuk','keluar'])->default('keluar');
            $table->bigInteger('nominal');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_kas');
    }
};
