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

        Schema::create('kaprodi', function (Blueprint $table) {
            $table->string('nip', 22);
            $table->unsignedBigInteger('id_prodi');
            
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaprodi');
    }
};
