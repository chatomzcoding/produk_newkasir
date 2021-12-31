<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToDistribusi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distribusi', function (Blueprint $table) {
            $table->unsignedBigInteger('cabang_id')->after('id');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('cascade');
            $table->unsignedBigInteger('kategori_id')->after('cabang_id');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distribusi', function (Blueprint $table) {
            //
        });
    }
}
