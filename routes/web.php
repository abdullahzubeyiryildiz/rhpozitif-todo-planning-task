<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
Route::get('/', function () {
    return view('tasks.result');
}); */

Route::get('/', [TaskController::class, 'index']);
