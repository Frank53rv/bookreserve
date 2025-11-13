<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InventoryRecordController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ReservationDetailController;
use App\Http\Controllers\ReservationHeaderController;
use App\Http\Controllers\ReturnDetailController;
use App\Http\Controllers\ReturnHeaderController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function (): void {
    Route::apiResources([
        'categories' => CategoryController::class,
        'books' => BookController::class,
        'clients' => ClientController::class,
        'reservations' => ReservationHeaderController::class,
        'reservation-details' => ReservationDetailController::class,
        'returns' => ReturnHeaderController::class,
        'return-details' => ReturnDetailController::class,
        'inventory-records' => InventoryRecordController::class,
        'movements' => MovementController::class,
    ]);
});
