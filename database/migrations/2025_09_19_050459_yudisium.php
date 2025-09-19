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
        $table->string('nama');
        $table->string('no_bp')->unique();
        $table->string('judul');
        $table->string('tgl_semi_proposal');
        $table->string('tgl_semi_hasil');
        $table->string('hasil_turnitin');
        $table->string('bukti_lunas');
        $table->string('khs');
        $table->string('kbs');
        $table->string('brsempro');
        $table->string('brsemhas');
        $table->string('full_skripsi');
        $table->string('matriks');
        $table->string('toefl');
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
