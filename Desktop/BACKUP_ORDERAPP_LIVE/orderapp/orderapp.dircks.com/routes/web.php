<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkupController;
use App\Http\Controllers\ThankYouController;

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


Route::get('order', 'OrdersController@create');
Route::post('add-order','OrdersController@store')->name('orderCreate');
Route::get('order/v2', 'OrdersController@createV2');
Route::get('quotation/{type}', 'QuotationController@create');
Route::post('add-quotation','QuotationController@store')->name('getQuotation');
Route::GET('get-vin-info','OrdersController@getVinDetails')->name('vinDetails');
Route::GET('get-terminal-list','OrdersController@getTerminalListData')->name('terminalListData');
Route::GET('get-terminal-details','OrdersController@getTerminalDetails')->name('terminalDetails');
Route::GET('update-sales-dispatcher','OrdersController@UpdateDisptachers')->name('UpdateDisptachers');
Route::POST('webhook-order-update','OrdersController@webhookOrderUpdate')->name('webhookOrderUpdate');
Route::get('quotation-list', 'QuotationController@list');

// Markups routes
Route::get('markups', [MarkupController::class, 'index'])->name('markups.index');
Route::post('markups', [MarkupController::class, 'store'])->name('markups.store');
Route::get('markups/{markup}', [MarkupController::class, 'show'])->name('markups.show');
Route::put('markups/{markup}', [MarkupController::class, 'update'])->name('markups.update');
Route::delete('markups/{markup}', [MarkupController::class, 'destroy'])->name('markups.destroy');

Route::get('/thankyou', [ThankYouController::class, 'showThankYouPage'])->name('thankyou');

