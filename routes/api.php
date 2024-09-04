<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//login
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);

//logout
Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');


//company
Route::get('/company', [\App\Http\Controllers\API\CompanyController::class, 'show'])->middleware('auth:sanctum');
