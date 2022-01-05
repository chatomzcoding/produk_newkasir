<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDistribusi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribusi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_distribusi',50);
            $table->string('no_faktur')->nullable();
            $table->date('tgl_faktur');
            $table->date('tgl_tempo')->nullable();
            $table->string('pembayaran',100);
            $table->longText('barang')->nullable();
            $table->integer('potongan')->nullable();
            $table->enum('status_stok',['proses','selesai']);
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
        Schema::dropIfExists('distribusi');
    }
}
