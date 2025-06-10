<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_login', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('username', 22);
            $table->string('ip_address', 45);
            $table->timestamp('waktu_aktivitas')->useCurrent();
            $table->enum('status', ['online', 'offline'])->default('online');
            $table->timestamp('waktu_logout')->nullable();

            $table->foreign('username')->references('username')->on('user')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_login');
    }
};
