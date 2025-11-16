<?php

use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\ClientController;
use App\Http\Controllers\Web\InventoryRecordController;
use App\Http\Controllers\Web\MovementController;
use App\Http\Controllers\Web\ReservationController;
use App\Http\Controllers\Web\ReservationDetailController;
use App\Http\Controllers\Web\SaleController;
use App\Http\Controllers\Web\ReturnController;
use App\Http\Controllers\Web\ReturnDetailController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::resource('categories', CategoryController::class)->names('web.categories');
Route::resource('books', BookController::class)->names('web.books');
Route::resource('clients', ClientController::class)->names('web.clients');
Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])
    ->name('web.reservations.status');
Route::resource('reservations', ReservationController::class)->names('web.reservations');
Route::resource('reservation-details', ReservationDetailController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->names('web.reservation-details');
Route::resource('returns', ReturnController::class)->names('web.returns');
Route::resource('return-details', ReturnDetailController::class)
    ->only(['create', 'store', 'edit', 'update', 'destroy'])
    ->names('web.return-details');
Route::resource('inventory-records', InventoryRecordController::class)->names('web.inventory-records');
Route::resource('movements', MovementController::class)->names('web.movements');
Route::get('sales/{sale}/ticket', [SaleController::class, 'ticket'])->name('web.sales.ticket');
Route::resource('sales', SaleController::class)->names('web.sales');
