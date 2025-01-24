<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [HomeController::class, 'view']);
Route::post('/order',  [OrderController::class, 'create']);
