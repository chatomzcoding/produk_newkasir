<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToRetur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('retur', function (Blueprint $table) {
            $table->unsignedBigInteger('distribusi_id')->after('id');
            $table->foreign('distribusi_id')->references('id')->on('distribusi')->onDelete('cascade');
            $table->unsignedBigInteger('cabang_id')->after('distribusi_id');
            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retur', function (Blueprint $table) {
            //
        });
    }
}
