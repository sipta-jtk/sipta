<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Hapus table list_kalimat_plagiarisme
        Schema::dropIfExists('list_kalimat_plagiarisme');

        // 3. Buat table keyword
        Schema::create('keyword', function (Blueprint $table) {
            $table->id('id_keyword');
            $table->string('nama_keyword');
            $table->timestamps();
        });

        // 6. Buat table dokumen_keyword (pivot table)
        Schema::create('dokumen_keyword', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dokumen');
            $table->unsignedBigInteger('id_keyword');
            
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen')->onDelete('cascade');
            $table->foreign('id_keyword')->references('id_keyword')->on('keyword')->onDelete('cascade');
            
            $table->primary(['id_dokumen', 'id_keyword']);
        });

        // 2. Tambahkan foreign key id_dokumen di list_jurnal_plagiarisme
        Schema::table('list_jurnal_plagiarisme', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dokumen')->nullable();
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen');
        });

        // 5. Tambah kategori dokumen Enum = digital_receipt di table dokumen
        Schema::table('dokumen', function (Blueprint $table) {
            // Tambahkan opsi 'digital_receipt' ke enum kategori yang sudah ada
            DB::statement("ALTER TABLE `dokumen` MODIFY `kategori` ENUM('yudisium', 'sidang', 'seminar3', 'seminar2', 'seminar1', 'plagiarisme', 'digital_receipt')");
        });
    }

    public function down()
    {
        // Rollback perubahan di dokumen
        Schema::table('dokumen', function (Blueprint $table) {
            // Kembalikan enum ke nilai semula
            DB::statement("ALTER TABLE `dokumen` MODIFY `kategori` ENUM('yudisium', 'sidang', 'seminar3', 'seminar2', 'seminar1', 'plagiarisme')");
        });

        // Hapus foreign key di list_jurnal_plagiarisme
        Schema::table('list_jurnal_plagiarisme', function (Blueprint $table) {
            $table->dropForeign(['id_dokumen']);
            $table->dropColumn('id_dokumen');
        });

        // Hapus tabel pivot
        Schema::dropIfExists('dokumen_keyword');
        
        // Hapus tabel keyword
        Schema::dropIfExists('keyword');
        
        // Buat ulang tabel list_kalimat_plagiarisme
        Schema::create('list_kalimat_plagiarisme', function (Blueprint $table) {
            $table->id('id_kalimat');
            $table->text('kalimat_plagiat');
            // Tambahkan kolom lain sesuai kebutuhan
            $table->timestamps();
        });
    }
};