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

        Schema::create('penilaian_kategori', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->unsignedBigInteger('id_kategori');
            $table->float('nilai_kategori');
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_penilaian');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_kategori');
    }
};
