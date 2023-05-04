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
        Schema::create('kode_induk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_ledger')->constrained('kode_ledger')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_induk');
            $table->string('nama');
            $table->enum('jenis',['kredit','debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kode_induk');
    }
};
