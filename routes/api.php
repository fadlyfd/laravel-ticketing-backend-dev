<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);

Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::post('/login/google', [App\Http\Controllers\Api\AuthController::class, 'loginGoogle']);

Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/events', [App\Http\Controllers\Api\EventController::class, 'index']);

Route::get('/event-categories', [App\Http\Controllers\Api\EventController::class, 'categories']);

Route::get('/events', [EventController::class, 'getAllEvents'])->middleware('auth:sanctum');

Route::post('/events', [EventController::class, 'create'])->middleware('auth:sanctum');

Route::get('/events/user/{id}', [EventController::class, 'getEventByUser'])->middleware('auth:sanctum');

Route::get('/event/{event_id}', [App\Http\Controllers\Api\EventController::class, 'detail']);

Route::post('/event/update/{event_id}', [App\Http\Controllers\Api\EventController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/event/{event_id}', [App\Http\Controllers\Api\EventController::class, 'delete'])->middleware('auth:sanctum');
Route::post('/order', [App\Http\Controllers\Api\OrderController::class, 'create'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}', [OrderController::class, 'getOrderByUserId'])->middleware('auth:sanctum');

Route::put('/orders/{id}', [OrderController::class, 'updateStatus'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}/vendor', [OrderController::class, 'getOrderByVendor'])->middleware('auth:sanctum');

Route::get('/orders/user/{id}/vendor/total', [OrderController::class, 'getOrderTotalByVendor'])->middleware('auth:sanctum');

Route::get('/tickets/user/{id}', [TicketController::class, 'getTickeUser'])->middleware('auth:sanctum');

Route::get('/vendors/user/{id}', [VendorController::class, 'getVendorByUser'])->middleware('auth:sanctum');
