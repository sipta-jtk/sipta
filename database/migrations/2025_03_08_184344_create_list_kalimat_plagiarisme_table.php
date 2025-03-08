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

        Schema::create('list_kalimat_plagiarisme', function (Blueprint $table) {
            $table->id('id_kalimat');
            $table->unsignedBigInteger('id_dokumen');
            $table->unsignedBigInteger('id_jurnal');
            $table->text('kalimat_plagiat');
            
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen');
            $table->foreign('id_jurnal')->references('id_jurnal')->on('list_jurnal_plagiarisme');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_kalimat_plagiarisme');
    }
};
