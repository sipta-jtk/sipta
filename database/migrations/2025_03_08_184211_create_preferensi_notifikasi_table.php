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

        Schema::create('preferensi_notifikasi', function (Blueprint $table) {
            $table->id('id_preferensi');
            $table->string('username', 22);
            $table->string('tipe_notifikasi', 255);
            $table->boolean('whatsapp');
            $table->boolean('in_app');
            $table->boolean('email');
            
            $table->foreign('username')->references('username')->on('user');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferensi_notifikasi');
    }
};
