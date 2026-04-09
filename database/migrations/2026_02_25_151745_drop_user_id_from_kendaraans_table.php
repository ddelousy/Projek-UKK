<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // hapus relasi
            $table->dropColumn('user_id');    // hapus kolom
        });
    }

    public function down(): void
    {
        Schema::table('kendaraan', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
        });
    }
};