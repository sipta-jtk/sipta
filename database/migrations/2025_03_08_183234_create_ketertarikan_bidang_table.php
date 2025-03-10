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

        Schema::create('ketertarikan_bidang', function (Blueprint $table) {
            $table->id('id_ketertarikan_bidang');
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_bidang');
            
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketertarikan_bidang');
    }
};
