<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            // 🔥 putus foreign key dulu
            $table->dropForeign(['tarif_id']);

            // 🔥 baru hapus kolom
            $table->dropColumn('tarif_id');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {

            $table->foreignId('tarif_id')
                ->constrained('tarif')
                ->cascadeOnDelete();
        });
    }
};