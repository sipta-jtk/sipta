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

        Schema::create('ruang_fasilitas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fasilitas');
            $table->unsignedBigInteger('id_ruangan');
            $table->integer('jumlah_fasilitas');
            
            $table->primary(['id_ruangan', 'id_fasilitas']);
            $table->foreign('id_fasilitas')->references('id_fasililtas')->on('fasilitas');
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruang_fasilitas');
    }
};
