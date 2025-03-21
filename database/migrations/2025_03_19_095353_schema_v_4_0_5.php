<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 25. Mengubah atribut nilai di table nilai_kategori menjadi nullable
        Schema::table('nilai_kategori', function (Blueprint $table) {
            $table->float('nilai')->nullable()->change();
        });

        // 26. Mengubah atribut nilai_kriteria di table nilai_kriteria menjadi nullable
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->float('nilai_kriteria')->nullable()->change();
        });

        // 27. Create table nilai_rubrik
        Schema::create('nilai_rubrik', function (Blueprint $table) {
            $table->string('nim', 22); 
            $table->string('nip', 22); 
            $table->unsignedBigInteger('id_rubrik'); 
            $table->float('nilai_rubrik'); 
            $table->enum('status_penilaian_dosen', ['belum_dinilai', 'sudah_dinilai'])->default('belum_dinilai'); 
            $table->timestamps(); 

            $table->foreign('id_rubrik')->references('id_rubrik')->on('rubrik')->onDelete('cascade');
            $table->foreign('nip')->references('nip')->on('dosen')->onDelete('cascade');
            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 25. Mengubah atribut nilai di table nilai_kategori menjadi not nullable
        Schema::table('nilai_kategori', function (Blueprint $table) {
            $table->float('nilai')->nullable(false)->change();
        });

        // 26. Mengubah atribut nilai_kriteria di table nilai_kriteria menjadi not nullable
        Schema::table('nilai_kriteria', function (Blueprint $table) {
            $table->float('nilai_kriteria')->nullable(false)->change();
        });

        // 27. Drop table nilai_rubrik
        Schema::dropIfExists('nilai_rubrik');
    }
};
