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
        Schema::create('jurnal', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kode_transaksi');
            $table->text('keterangan');
            $table->bigInteger('kode_akun');
            $table->bigInteger('kode_lawan');
            $table->enum('tipe',['debit','kredit']);
            $table->bigInteger('nominal');
            $table->bigInteger('id_detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal');
    }
};
