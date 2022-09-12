<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn() => Inertia::render('Dashboard', []))->name('/');
Route::get('appartments', [\App\Http\Controllers\DashboardController::class, 'appartments'])->name('appartments');
Route::get('locations', [\App\Http\Controllers\DashboardController::class, 'locations'])->name('locations');
