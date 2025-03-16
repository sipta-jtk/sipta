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

        Schema::create('konfirmasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_penjadwalan');
            $table->string('nip', 22);
            $table->enum('status_konfirmasi', ['disetujui', 'tidak_disetujui']);
            
            $table->foreign('id_penjadwalan')->references('id_penjadwalan')->on('penjadwalan');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfirmasi');
    }
};
