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

        Schema::create('prioritas_pembimbing', function (Blueprint $table) {
            $table->id('id_prioritas_pembimbing');
            $table->unsignedBigInteger('id_pengajuan');
            $table->string('nip', 22);
            $table->integer('urutan_prioritas');
            
            $table->foreign('id_pengajuan')->references('id_pengajuan_pembimbing')->on('pengajuan_pembimbing');
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::enableForeignKeyConstraints();
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prioritas_pembimbing');
    }
};
