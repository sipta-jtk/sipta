<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeActionEnumToStringInLogAktivitas extends Migration
{
    public function up()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Tambah kolom sementara varchar
            $table->string('action_temp', 255)->nullable();
        });

        // Salin data dari kolom enum ke kolom string
        DB::statement('UPDATE log_aktivitas SET action_temp = action');

        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Hapus kolom lama (enum)
            $table->dropColumn('action');
        });

        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Rename kolom baru ke nama lama
            $table->renameColumn('action_temp', 'action');
        });
    }

    public function down()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Tambah kolom enum sementara
            $table->enum('action_temp', ['upload','edit','delete','download','review'])->nullable();
        });

        \DB::statement('UPDATE log_aktivitas SET action_temp = action');

        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropColumn('action');
        });

        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->renameColumn('action_temp', 'action');
        });
    }
}
