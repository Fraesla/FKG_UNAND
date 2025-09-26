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
        Schema::create('saps', function (Blueprint $table) {
            $table->id();
            $table->string('id_mahasiswa');
            $table->string('jml_point_a');
            $table->string('jml_point_b');
            $table->string('jml_point_c');
            $table->string('total');
            $table->string('predikat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saps');
    }
};
