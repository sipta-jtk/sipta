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

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 22)->primary();
            $table->year('tahun_masuk');
            $table->string('kelas', 4);
            $table->unsignedBigInteger('id_prodi');
            $table->enum('status_ta', ['mahasiswa_ta', 'mahasiswa_non_ta']);
            $table->float('nilai_akhir_ta');
            $table->unsignedBigInteger('id_kota');
            
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_kota')->references('id_kota')->on('kota');
            $table->foreign('nim')->references('username')->on('user');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
