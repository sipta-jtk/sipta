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

        Schema::create('ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('kode_ruangan', 6);
            $table->string('nama_ruangan', 127)->unique();
            $table->enum('status_ruangan', ['tersedia', 'tidak_tersedia']);
            $table->string('kode_gedung', 1);
            $table->string('link_ruangan', 45);
            
            $table->foreign('kode_gedung')->references('kode_gedung')->on('gedung');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
