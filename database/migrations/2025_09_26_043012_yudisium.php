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
        Schema::create('yudisium', function (Blueprint $table) {
        $table->id();
        $table->string('id_mahasiswa');
        $table->string('judul');
        $table->string('tgl_semi_proposal');
        $table->string('tgl_semi_hasil');
        $table->text('hasil_turnitin');
        $table->text('bukti_lunas');
        $table->text('khs');
        $table->text('kbs');
        $table->text('brsempro');
        $table->text('brsemhas');
        $table->text('full_skripsi');
        $table->text('matriks');
        $table->text('toefl');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yudisium');
    }
};
