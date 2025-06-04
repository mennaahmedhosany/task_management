<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'registerUser']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {

    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('tasks', TaskController::class)
    ->middleware('auth:sanctum')
    ->only(['index', 'store', 'update', 'destroy']);
