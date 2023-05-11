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
        Schema::table('suku_bunga_koperasi', function (Blueprint $table) {
            $table->string('jenis')->nullable()->comment('pinjaman atau tabungan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suku_bunga_koperasi', function (Blueprint $table) {
            //
        });
    }
};
