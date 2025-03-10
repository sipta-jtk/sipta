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

        Schema::create('rubrik_penilaian', function (Blueprint $table) {
            $table->id('id_rubrik');
            $table->unsignedBigInteger('id_kategori');
            $table->string('judul_rubrik', 100);
            $table->text('detail_rubrik')->nullable();
            $table->integer('bobot_rubrik');
            
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_penilaian')->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrik_penilaian');
    }
};
