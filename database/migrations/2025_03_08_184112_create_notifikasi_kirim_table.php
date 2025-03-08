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

        Schema::create('notifikasi_kirim', function (Blueprint $table) {
            $table->id('id_kirim');
            $table->unsignedBigInteger('id_notifikasi');
            $table->string('username', 22);
            $table->string('kanal', 255);
            $table->string('status', 255);
            $table->datetime('waktu_kirim');
            $table->text('respon_log');
            
            $table->foreign('id_notifikasi')->references('id_notifikasi')->on('notifikasi');
            $table->foreign('username')->references('username')->on('user');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_kirim');
    }
};
