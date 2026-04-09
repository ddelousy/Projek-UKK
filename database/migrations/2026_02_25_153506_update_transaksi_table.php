<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->string('card_id')
                  ->after('kendaraan_id');

            $table->enum('status', ['in', 'out', 'done'])
                  ->default('in')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->dropColumn('card_id');

            $table->enum('status', ['masuk', 'keluar'])
                  ->default('masuk')
                  ->change();
        });
    }
};