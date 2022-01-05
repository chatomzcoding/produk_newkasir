<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->string('status_transaksi');
            $table->string('status_pembeli',['umum','member']);
            $table->string('kode_pelanggan')->nullable();
            $table->string('tipe_pembayaran')->nullable();
            $table->string('tipe_orderan')->nullable();
            $table->integer('diskon')->nullable();
            $table->integer('uang_pembeli');
            $table->longText('keranjang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi');
    }
}
