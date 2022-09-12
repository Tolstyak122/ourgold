<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('appartments', [\App\Http\Controllers\ApiController::class, 'appartments'])->name('appartments');
Route::get('locations', [\App\Http\Controllers\ApiController::class, 'locations'])->name('locations');
Route::get('room_types', [\App\Http\Controllers\ApiController::class, 'room_types'])->name('room_types');
Route::get('furnitures', [\App\Http\Controllers\ApiController::class, 'furnitures'])->name('furnitures');
Route::get('furniture_sets', [\App\Http\Controllers\ApiController::class, 'furniture_sets'])->name('furniture_sets');
Route::get('furniture_types', [\App\Http\Controllers\ApiController::class, 'furniture_types'])->name('furniture_types');
