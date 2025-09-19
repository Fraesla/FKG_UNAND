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
        Schema::create('ta', function (Blueprint $table) {
        $table->id();
        $table->string('no_bp')->unique();
        $table->string('nama_mahasiswa');
        $table->string('dosen_pembimbing');
        $table->string('tgl_bimbingan');
        $table->text('catatan');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ta');
    }
};
