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

        Schema::create('penilaian_rubrik', function (Blueprint $table) {
            $table->string('nim', 22);
            $table->string('nip', 20);
            $table->unsignedBigInteger('id_rubrik');
            $table->float('nilai_rubrik');
            $table->text('detail_feedback')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            
            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('nip')->references('nip')->on('dosen');
            $table->foreign('id_rubrik')->references('id_rubrik')->on('rubrik_penilaian');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_rubrik');
    }
};
