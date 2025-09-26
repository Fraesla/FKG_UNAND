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
        Schema::create('seminar_hasil', function (Blueprint $table) {
        $table->id();
        $table->string('id_mahasiswa');
        $table->string('dosen_pembimbing_1');
        $table->string('dosen_pembimbing_2');
        $table->string('penguji_1');
        $table->string('penguji_2');
        $table->string('penguji_3');
        $table->text('surat_hasil');
        $table->text('file_draft');
        $table->text('bukti_izin');
        $table->text('lembar_jadwal');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seminar_hasil');
    }
};
