<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\BitcoinController;
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

Route::get('/', function () {
    return redirect('/bitcoin'); //return view('welcome');
});

Route::get('bitcoin', [BitcoinController::class, 'index'])
    ->name('bitcoin.index');
Route::get('bitcoin-snapshots', [BitcoinController::class, 'snapshots'])
    ->name('bitcoin.snapshots');
Route::post('bitcoin-subscribe-for-price-reach', [BitcoinController::class, 'subscribe'])
    ->name('bitcoin.subscribe-for-price-reach');
