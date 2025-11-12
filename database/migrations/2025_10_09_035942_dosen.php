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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('nip')->unique();
            $table->string('nidn')->unique();
            $table->string('gender')->nullable();
            $table->string('pangol')->nullable();
            $table->string('napater')->nullable();
            $table->string('napaber')->nullable();
            $table->string('jf')->nullable();
            $table->string('js')->nullable();
            $table->string('najater')->nullable();
            $table->string('penter')->nullable();
            $table->string('keterangan')->nullable();
            $table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
