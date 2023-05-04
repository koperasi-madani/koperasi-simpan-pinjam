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
        Schema::create('kode_akun', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_induk')->constrained('kode_induk')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_akun');
            $table->string('nama_akun');
            $table->enum('jenis',['kredit','debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_akun');
    }
};
