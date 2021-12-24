<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBarang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang',100);
            $table->string('nama_barang');
            $table->integer('kategori_id');
            $table->integer('satuan_id');
            $table->string('harga_beli');
            $table->string('harga_jual');
            $table->integer('stok');
            $table->text('gambar')->nullable();
            $table->string('kode_barcode')->nullable();
            $table->string('merk')->nullable();
            $table->string('status_barang')->nullable();
            $table->string('produsen_id')->nullable();
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
        Schema::dropIfExists('barang');
    }
}
