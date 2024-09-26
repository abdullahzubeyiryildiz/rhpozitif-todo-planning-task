<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/todos', [TaskApiController::class, 'index']);
Route::get('/developers', [TaskApiController::class, 'distributeTasks']);
