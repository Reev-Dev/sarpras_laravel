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
        Schema::create('school_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pembelian');
            $table->string('nama_barang');
            $table->string('kode')->unique();
            $table->string('harga_satuan');
            $table->integer('jumlah_baik')->default(0);
            $table->integer('jumlah_rusak')->default(0);
            $table->string('total_harga');
            $table->string('pembeli');
            $table->string('toko');
            $table->text('deskripsi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_purchases');
    }
};
