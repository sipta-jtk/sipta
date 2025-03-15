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

        Schema::create('kategori_penilaian', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->unsignedBigInteger('id_form_penilaian');
            $table->string('nama_kategori', 50);
            $table->integer('bobot_kategori');
            
            $table->foreign('id_form_penilaian')->references('id_form_penilaian')->on('form_penilaian')->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_penilaian');
    }
};
