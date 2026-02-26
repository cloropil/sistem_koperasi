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
        Schema::table('piutangs', function (Blueprint $table) {
            $table->integer('jangka_pinjaman')->nullable()->comment('Durasi pinjaman dalam bulan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piutangs', function (Blueprint $table) {
            $table->dropColumn('jangka_pinjaman');
        });
    }
};
