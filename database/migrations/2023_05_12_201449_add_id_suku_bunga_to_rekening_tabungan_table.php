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
        Schema::table('rekening_tabungan', function (Blueprint $table) {
            $table->bigInteger('id_suku_bunga')->nullable()->after('id_kode_akun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekening_tabungan', function (Blueprint $table) {
            //
        });
    }
};
