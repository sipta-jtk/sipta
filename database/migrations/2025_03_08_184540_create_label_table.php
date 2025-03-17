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

        Schema::create('label', function (Blueprint $table) {
            $table->id('id_label');
            $table->string('nama_label', 255)->unique();
            $table->unsignedBigInteger('id_kota');
            
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label');
    }
};
