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
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('konfirmasi_nip_foreign');
            $table->string('nip')->nullable()->change();
            $table->foreign('nip')->references('nip')->on('dosen');
        });

        Schema::table('penjadwalan', function (Blueprint $table){
            $table->string('nama_ruangan', 24)->default('Belum Ditentukan');
            $table->enum('status', ['fix', 'batal', 'pending'])->default('pending');
            
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifikasi_berkas_pengajuan', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropForeign('konfirmasi_nip_foreign');
            $table->string('nip')->nullable()->change();
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::table('penjadwalan', function (Blueprint $table) {
            $table->dropColumn('nama_ruangan');
        });
    }
};
