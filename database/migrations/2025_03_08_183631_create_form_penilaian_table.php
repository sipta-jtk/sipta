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

        Schema::create('form_penilaian', function (Blueprint $table) {
            $table->id('id_form_penilaian');
            $table->string('nama_formulir_penilaian', 50);
            $table->string('nip', 20);
            $table->year('tahun_ajaran');
            $table->enum('status_form', ['pending', 'draft', 'published', 'used', 'finished']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('nip')->references('nip')->on('dosen');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_penilaian');
    }
};
