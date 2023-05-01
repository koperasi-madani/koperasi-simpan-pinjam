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
            $table->foreignId('nasabah_id')->constrained('nasabah')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('no_rekening');
            $table->date('tgl_simpanan')->nullable();
            $table->date('tgl_penarikan')->nullable();
            $table->date('tgl_transaksi')->nullable();
            $table->bigInteger('saldo_anggota')->nullable()->default(0);
            $table->bigInteger('jumlah_simpanan')->nullable()->default(0);
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
