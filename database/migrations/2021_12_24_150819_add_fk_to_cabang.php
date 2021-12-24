<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToCabang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->after('id');
            $table->foreign('client_id')->references('id')->on('client')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->after('client_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cabang', function (Blueprint $table) {
            //
        });
    }
}
