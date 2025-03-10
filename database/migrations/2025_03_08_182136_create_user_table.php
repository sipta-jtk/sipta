<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('user', function (Blueprint $table) {
            $table->string('username', 22)->primary();
            $table->string('nama', 100);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->enum('role_user', ['mahasiswa', 'dosen']);
            $table->string('no_whatsapp', 15);
            $table->string('photo', 255);
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
