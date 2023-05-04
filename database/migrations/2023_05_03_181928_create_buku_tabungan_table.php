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
        Schema::create('buku_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rekening_tabungan')->constrained('rekening_tabungan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_kode_akun')->constrained('kode_akun')->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('tgl_transaksi');
            $table->string('nominal_transaksi');
            $table->string('saldo');
            $table->string('validasi')->nullable();
            $table->enum('jenis',['masuk','keluar']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_tabungan');
    }
};
