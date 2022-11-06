<?php

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
    return view('welcome');
});


Route::get('/play/xrpl-transaction-mutation-parser', [App\Http\Controllers\PlayController::class, 'txmutationparser'])->name('play.txmutationparser.index');
Route::get('/play/xrpl-orderbook-reader', [App\Http\Controllers\PlayController::class, 'orderbookreader'])->name('play.orderbookreader.index');
