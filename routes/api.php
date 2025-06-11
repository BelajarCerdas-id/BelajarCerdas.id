<?php

use App\Http\Controllers\PaymentFeaturesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/midtrans-callback', [PaymentFeaturesController::class, 'callback']);
