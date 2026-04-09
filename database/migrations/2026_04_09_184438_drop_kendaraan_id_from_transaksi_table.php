<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['kendaraan_id']);
            $table->dropColumn('kendaraan_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraan');
        });
    }
};
