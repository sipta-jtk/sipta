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

        Schema::create('kota', function (Blueprint $table) {
            $table->id('id_kota');
            $table->text('judul_ta');
            $table->unsignedBigInteger('id_bidang');
            $table->string('nama_kota', 255);
            $table->year('tahun_kota');
            $table->enum('status_kota', ['pra_kota', 'aktif', 'lulus', 'bubar']);
            
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kota');
    }
};
