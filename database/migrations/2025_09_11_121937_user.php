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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('level');
            $table->enum('status',['0','1'])->default('1');
            $table->timestamps();
        });

        DB::table('user')->insert([
            ['username' => 'admin', 'password' => bcrypt('Admin2025'), 'level' => 'admin', 'status' => '1' ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
