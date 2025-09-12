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
        Schema::create('absen_dosen', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->string('id_dosen');
            $table->string('id_jadwal_dosen')->unique();
            $table->text('qr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_dosen');
    }
};
