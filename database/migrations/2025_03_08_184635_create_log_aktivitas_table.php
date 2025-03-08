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

        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id('id_log_aktivitas');
            $table->unsignedBigInteger('id_kota');
            $table->string('username', 22);
            $table->unsignedBigInteger('id_dokumen');
            $table->enum('action', ['upload', 'edit', 'delete', 'download', 'review']);
            $table->timestamp('waktu_aktivitas')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('id_kota')->references('id_kota')->on('kota')->onDelete('cascade');
            $table->foreign('username')->references('username')->on('user')->onDelete('cascade');
            $table->foreign('id_dokumen')->references('id_dokumen')->on('dokumen')->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
