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
        Schema::create('suku_bunga_koperasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_akun')->constrained('kode_akun')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nama')->nullable();
            $table->bigInteger('suku_bunga')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suku_bunga_koperasi');
    }
};
