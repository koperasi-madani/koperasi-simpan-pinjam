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
        Schema::create('penarikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate()->after('id_rekening_tabungan');
            $table->foreignId('id_rekening_tabungan')->constrained('rekening_tabungan')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('kode_penarikan');
            $table->date('tgl_setor');
            $table->string('nominal_setor');
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
        Schema::dropIfExists('penarikan');
    }
};
