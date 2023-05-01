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
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id();
            $table->string('no_anggota');
            $table->foreignId('users_id')->constrained('users','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nama');
            $table->string('no_hp');
            $table->text('alamat');
            $table->datetime('tgl');
            $table->bigInteger('sim_pokok')->default(0);
            $table->bigInteger('sim_wajib')->default(0);
            $table->bigInteger('sim_sukarela')->default(0);
            $table->enum('status',['aktif','non-aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};
