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
        Schema::create('transaksi_pemindah_rekening', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->bigInteger('id_user');
            $table->date('tanggal');
            $table->bigInteger('kode_akun');
            $table->enum('tipe',['masuk','keluar']);
            $table->decimal('total',12,2);
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pemindah_rekening');
    }
};
