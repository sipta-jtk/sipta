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

        Schema::create('dokumen', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->string('judul', 255);
            $table->float('persentase_plagiarisme');
            $table->boolean('highlight_dokumen');
            $table->enum('status_plagiarisme', ['plagiarisme', 'tidak_plagiarisme']);
            $table->text('review');
            $table->enum('kategori', ['laporan', 'poster', 'presentasi']);
            $table->text('deskripsi')->nullable();
            $table->integer('versi');
            $table->float('ukuran_file');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('id_kota');
            $table->unsignedBigInteger('id_label')->nullable();
            $table->unsignedBigInteger('id_subkategori')->nullable();
            $table->string('username', 22);
            $table->enum('status_berkas', ['valid', 'tidak_valid', 'belum_unggah', 'ditunda']);
            $table->timestamp('uploaded_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('cascade');
            $table->foreign('id_label')->references('id_label')->on('label')->onDelete('cascade');
            $table->foreign('id_subkategori')->references('id_subkategori')->on('subkategori')->onDelete('cascade');
            $table->foreign('username')->references('username')->on('user')->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
