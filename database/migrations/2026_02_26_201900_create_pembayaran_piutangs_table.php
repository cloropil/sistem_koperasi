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
        Schema::create('pembayaran_piutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piutang_id')->constrained('piutangs')->onDelete('cascade');
            $table->integer('bulan_ke')->comment('Bulan ke berapa dari jangka peminjaman');
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('jumlah_pembayaran', 15, 2)->default(0)->comment('Jumlah yang harus dibayar per bulan');
            $table->decimal('jumlah_dibayar', 15, 2)->default(0)->comment('Jumlah yang sudah dibayar');
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            $table->date('tanggal_pembayaran')->nullable()->comment('Tanggal pembayaran dilakukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutangs');
    }
};
