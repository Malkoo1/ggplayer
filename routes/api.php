<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/get-appid', [ApiController::class, 'getAppId']);

Route::get('/app-status/{id}', [ApiController::class, 'getStatus']);
