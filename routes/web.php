<?php

use App\Http\Controllers\Client\CabangController;
use App\Http\Controllers\Gudang\DistribusiController;
use App\Http\Controllers\Gudang\ProdusenController;
use App\Http\Controllers\Gudang\ReturController;
use App\Http\Controllers\Gudang\SupplierController;
use App\Http\Controllers\Kasir\BarangController;
use App\Http\Controllers\Kasir\LaporanController;
use App\Http\Controllers\Kasir\UseraksesController;
use App\Http\Controllers\Sistem\KategoriController;
use App\Http\Controllers\Sistem\KeuanganController;
use App\Http\Controllers\Sistem\SatuanController;
use App\Http\Controllers\Sistem\TransaksiController;
use App\Http\Controllers\Superadmin\ClientController;
use App\Http\Controllers\Superadmin\ListdataController;
use App\Http\Controllers\UserController;
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

    // CLIENT
    Route::middleware(['client'])->group(function () {
        Route::resource('cabang', CabangController::class);
    });
    
    Route::resource('user', UserController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('satuan', SatuanController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('produsen', ProdusenController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('userakses', UseraksesController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('retur', ReturController::class);
    Route::resource('keuangan', KeuanganController::class);

    // TRANSAKSI
    Route::get('kasir/transaksi/loadbarang','App\Http\Controllers\Sistem\TransaksiController@loadbarang')->name('loadBarang');

    // PAGE
    Route::get('cetakdata','App\Http\Controllers\Sistem\CetakController@cetak');
    Route::get('datalaporan/{sesi}','App\Http\Controllers\Sistem\LaporanController@index');
    Route::get('migrasi','App\Http\Controllers\Sistem\MigrasidatabaseController@index');
    Route::get('page/{sesi}','App\Http\Controllers\Sistem\MigrasidatabaseController@page');

    
});
