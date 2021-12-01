<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Example; 
use App\Http\Livewire\Members; //Load class Members 
use App\Imports\DataPenduduk;
use App\Imports\KategoriartikelImport;
use App\Imports\PendudukImport;
use App\Imports\PendudukpenyesuainaImport;
use App\Imports\PenduduksimpleImport;
use Maatwebsite\Excel\Facades\Excel;

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
    
    // CETAK
    Route::get('cetakdata','App\Http\Controllers\CetakController@cetak');

    Route::resource('user', 'App\Http\Controllers\Admin\UserController');
    
});
