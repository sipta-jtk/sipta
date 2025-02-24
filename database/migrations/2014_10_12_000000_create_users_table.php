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
        Schema::create('users', function (Blueprint $table) {
            $table->string('username', 22)->primary();
            $table->string('nama', 100);
            $table->string('email', 255)->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->enum('role_user',['mahasiswa','dosen']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
