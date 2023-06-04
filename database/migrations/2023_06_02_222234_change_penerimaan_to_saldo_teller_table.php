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
        Schema::table('saldo_teller', function (Blueprint $table) {
            $table->bigInteger('penerimaan')->default(0)->change();
            $table->bigInteger('pembayaran')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldo_teller', function (Blueprint $table) {
            //
        });
    }
};
