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

        Schema::create('kehadiran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_penjadwalan');
            $table->string('username', 22);
            $table->enum('status_hadir', ['hadir', 'tidak_hadir']);
            
            $table->foreign('id_penjadwalan')->references('id_penjadwalan')->on('penjadwalan');
            $table->foreign('username')->references('username')->on('user');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
