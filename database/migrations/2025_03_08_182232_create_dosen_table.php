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

        Schema::create('dosen', function (Blueprint $table) {
            $table->string('nip', 22)->primary();
            $table->integer('maks_bimbingan_d4');
            $table->integer('maks_bimbingan_d3');
            $table->unsignedBigInteger('id_kbk');
            $table->string('id_dosen', 3);
            $table->string('kode_dosen', 8);
            $table->enum('status_dosen', ['aktif', 'nonaktif']);
            $table->enum('role_dosen', ['dosen', 'koordinator_ta', 'kajur', 'dosen_pembimbing']);
            
            $table->foreign('id_kbk')->references('id_kbk')->on('kbk');
            $table->foreign('nip')->references('username')->on('user');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
