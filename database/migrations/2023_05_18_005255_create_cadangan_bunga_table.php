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
        Schema::create('cadangan_bunga', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->bigInteger('id_nasabah');
            $table->bigInteger('suku_bunga');
            $table->bigInteger('saldo');
            $table->bigInteger('bunga_cadangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cadangan_bunga');
    }
};
