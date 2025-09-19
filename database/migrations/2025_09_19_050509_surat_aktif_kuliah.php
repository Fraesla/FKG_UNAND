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
        Schema::create('surat_aktif_kuliah', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('tgl_lahir');
        $table->string('no_bp')->unique();
        $table->string('semester');
        $table->string('tahun_akademik');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_aktif_kuliah');
    }
};
