<?php

use App\Http\Controllers\Superadmin\ClientController;
use App\Http\Controllers\Superadmin\ListdataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// homepage
Route::get('/','App\Http\Controllers\HomepageController@index');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {
    Route::get('/dashboard','App\Http\Controllers\HomeController@index')->name('dashboard');
    
    // SUPERADMIN
    Route::middleware(['superadmin'])->group(function () {
        Route::resource('listdata', ListdataController::class);
        Route::resource('client', ClientController::class);
    });

    // CETAK
    Route::get('cetakdata','App\Http\Controllers\CetakController@cetak');

    Route::resource('user', 'App\Http\Controllers\Admin\UserController');
    
});
