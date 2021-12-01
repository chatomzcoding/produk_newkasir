<?php

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
Route::resource('user','App\Http\Controllers\Api\UserController');
Route::get('chatforum/{id}','App\Http\Controllers\Api\ForumController@chatforum');
