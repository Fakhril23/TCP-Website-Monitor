<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testings', function (Blueprint $table) {
            // Waktu respons pengecekan TCP, dalam milidetik. Nullable karena
            // testing lama (sebelum kolom ini ada) tidak punya nilai ini.
            $table->unsignedInteger('response_time')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testings', function (Blueprint $table) {
            $table->dropColumn('response_time');
        });
    }
};
