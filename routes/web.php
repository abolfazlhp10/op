<?php

// header('Access-Control-Allow-Origin:*');

use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Mail\forgetPassMail;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
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

Route::get('user/{id}/edit', [UsersController::class, 'edit'])->middleware('auth:santcum');
Route::put('user/{id}', [UsersController::class, 'update']);
Route::get('user/{email}', [UsersController::class, 'checkUserEmail']);


Route::get('forgetPassword/{email}',[UsersController::class,'sendForgetLink']);

Route::post('forgetPassword/{email}/{token}',[UsersController::class,'forgetPassword']);
