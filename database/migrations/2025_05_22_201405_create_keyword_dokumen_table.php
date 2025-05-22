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

        Schema::create('keyword_dokumen', function (Blueprint $table) {
            $table->primary(['id_dokumen', 'id_keyword']);
            $table->unsignedBigInteger('id_dokumen');
            $table->unsignedBigInteger('id_keyword');
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen');
            $table->foreign('id_keyword')->references('id_keyword')->on('keyword');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keyword_dokumen');
    }
};
