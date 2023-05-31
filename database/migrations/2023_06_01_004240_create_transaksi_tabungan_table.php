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
        Schema::create('transaksi_tabungan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_nasabah');
            $table->bigInteger('id_user');
            $table->string('kode');
            $table->bigInteger('nominal');
            $table->enum('jenis',['masuk','keluar']);
            $table->enum('status',['pending','ditolak','setuju']);
            $table->date('tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_tabungan');
    }
};
