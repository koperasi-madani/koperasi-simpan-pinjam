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
        Schema::create('rekening_tabungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_kode_akun')->constrained('kode_akun')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('no_rekening');
            $table->date('tgl_transaksi')->nullable();
            $table->date('tgl')->nullable();
            $table->bigInteger('saldo_awal')->nullable()->default(0);
            $table->enum('status',['aktif','non-aktif'])->default('aktif');
            $table->text('ket')->nullable()->default('Pembukaan rekening');
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
