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

        Schema::create('mahasiswa_dosen_dokumen', function (Blueprint $table) {
            $table->string('nip', 22);
            $table->string('nim', 22);
            $table->unsignedBigInteger('id_dokumen');
            
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_dosen_dokumen');
    }
};
