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
       Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nobp')->unique();
            $table->string('nama')->nullable();
            $table->string('gender')->nullable();
            $table->string('alamat')->nullable();
            $table->string('contact')->nullable();
            $table->string('id_tahun_ajaran')->nullable();
            $table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
