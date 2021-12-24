<?php

use App\Http\Controllers\Api\ListdataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// api produk
Route::resource('client','App\Http\Controllers\Api\ClientController');
Route::resource('kategori','App\Http\Controllers\Api\KategoriController');
Route::resource('barang','App\Http\Controllers\Api\BarangController');
Route::resource('transaksi','App\Http\Controllers\Api\TransaksiController');
Route::resource('user','App\Http\Controllers\Api\UserController');
Route::resource('listdata', ListdataController::class);

// mobile api
Route::get('userakses/{user}','App\Http\Controllers\Api\MobileController@userakses');
Route::post('ceklogin','App\Http\Controllers\Api\MobileController@ceklogin');
Route::post('ubahpassword','App\Http\Controllers\Api\MobileController@ubahpassword');
