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

        Schema::create('alokasi_pembimbing', function (Blueprint $table) {
            $table->id('id_alokasi_pembimbing');
            $table->unsignedBigInteger('id_pengajuan_pembimbing');
            $table->string('nip', 22);
            $table->integer('urutan_prioritas_terpilih');
            
            $table->foreign('id_pengajuan_pembimbing')->references('id_pengajuan_pembimbing')->on('pengajuan_pembimbing');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alokasi_pembimbing');
    }
};
