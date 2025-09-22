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
            $table->string('nip')->unique();
            $table->string('pango'); 
            $table->string('jabatan'); 
            $table->string('nama_mhs');
            $table->string('tmp_lahir_mhs');
            $table->string('tgl_lahir_mhs');
            $table->string('no_bp')->unique();
            $table->string('semester');
            $table->string('tahun_akademik');
            $table->string('nama_ort');
            $table->string('tmp_lahir_ort');
            $table->string('tgl_lahir_ort');
            $table->string('nip_ort')->unique();
            $table->string('pango_ort');
            $table->string('jabatan_ort');
            $table->string('instansi_ort');
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
