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
        Schema::create('saldo_teller', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->bigInteger('id_user');
            $table->enum('status',['penerimaan','pembayaran']);
            $table->bigInteger('penerimaan');
            $table->bigInteger('pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_teller');
    }
};
