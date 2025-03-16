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

        Schema::create('penjadwalan', function (Blueprint $table) {
            $table->id('id_penjadwalan');
            $table->integer('sesi');
            $table->enum('agenda', ['seminar_1', 'seminar_2', 'seminar_3', 'sidang']);
            $table->unsignedBigInteger('id_ruangan');
            $table->date('tanggal');
            $table->unsignedBigInteger('id_kota');
            $table->string('nip', 22);
            
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangan');
            $table->foreign('id_kota')->references('id_kota')->on('kota');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjadwalan');
    }
};
