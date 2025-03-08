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

        Schema::create('pengajuan_pembimbing', function (Blueprint $table) {
            $table->id('id_pengajuan_pembimbing');
            $table->unsignedBigInteger('id_kota');
            $table->enum('status_pengajuan', ['pending', 'diterima'])->default('pending');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('id_kota')->references('id_kota')->on('kota');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pembimbing');
    }
};
