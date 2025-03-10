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

        Schema::create('pengajuan_pisah_kota', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->string('nim', 22);
            $table->unsignedBigInteger('id_kota');
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('id_kota')->references('id_kota')->on('kota');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pisah_kota');
    }
};
