<?php

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('login', [UsersController::class, 'login']);
Route::post('signup', [UsersController::class, 'signup']);
Route::post('logout', [UsersController::class, 'logout']);
Route::get('google', [UsersController::class, 'next']);
Route::get('callback-google', [UsersController::class, 'handleCallback']);
Route::get('user/{id}/edit', [UsersController::class, 'edit'])->middleware('auth:santcum');
Route::put('user/{id}', [UsersController::class, 'update']);
