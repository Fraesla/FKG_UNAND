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
        Schema::create('surat_izin', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->string('nama');
            $table->string('no_bp')->unique();
            $table->text('alamat');
            $table->string('judul_penelitian');
            $table->string('gmail');
            $table->string('no_hp');
            $table->string('dosen_pembimbing_1');
            $table->string('dosen_pembimbing_2');
            $table->text('isi_surat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_izin');
    }
};
