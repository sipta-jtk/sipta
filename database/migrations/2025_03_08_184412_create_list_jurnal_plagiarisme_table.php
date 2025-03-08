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

        Schema::create('list_jurnal_plagiarisme', function (Blueprint $table) {
            $table->id('id_jurnal');
            $table->string('link_jurnal', 255);
            $table->string('judul', 255);
            $table->float('persentase_kemunculan');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_jurnal_plagiarisme');
    }
};
