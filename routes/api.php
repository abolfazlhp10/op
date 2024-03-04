<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ConttactController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\UsersController;
use Illuminate\Contracts\Cache\Store;
use App\Http\Controllers\ProjectteamController;

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


Route::middleware(['auth:sanctum'])->group(function(){

    /*MENUS*/

    Route::get('/menus/showclint', [\App\Http\Controllers\MenuController::class, 'showclint']);


    /*CARTS*/

    Route::get('/carts/show', [\App\Http\Controllers\CartController::class, 'show']);
    Route::get('/carts/singlepage/{cart}', [\App\Http\Controllers\CartController::class, 'show']);


    /*CONTACT*/

    Route::post('/sendemail', [\App\Http\Controllers\ConttactController::class, 'sendemail']);

    /*LOGOS*/


    Route::get('/logos/show', [\App\Http\Controllers\LogoController::class, 'show']);

    /*LINKINSAGRAM*/


    Route::get('/links/show', [\App\Http\Controllers\LinkinsagramController::class, 'show']);

    /*orders*/
    Route::post('/orders/store', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::post('/orders/showall', [\App\Http\Controllers\OrderController::class, 'getAllOrders']);
    Route::middleware('auth:api')->get('/orders/order-count', [OrderController::class, 'getAllOrders']);

});

Route::middleware(['admin','auth:sanctum'])->group(function(){
    Route::post('/menus/store', [MenuController::class, 'store']);
    Route::post('/menus/update/{menu}', [\App\Http\Controllers\MenuController::class, 'update']);
    Route::get('/menus/showadmin', [\App\Http\Controllers\MenuController::class, 'showadmin']);
    Route::delete('/menus/destroy/{menu}', [\App\Http\Controllers\MenuController::class, 'destroy']);

    Route::post('/carts/store', [\App\Http\Controllers\CartController::class, 'store']);
    Route::post('/carts/update/{cart}', [\App\Http\Controllers\CartController::class, 'update']);
    Route::delete('/carts/destroy/{cart}', [\App\Http\Controllers\CartController::class, 'destroy']);

    Route::post('/logos/store', [\App\Http\Controllers\LogoController::class, 'store']);
    Route::post('/logos/update/{logo}', [\App\Http\Controllers\LogoController::class, 'update']);

    Route::post('/links/store', [\App\Http\Controllers\LinkinsagramController::class, 'store']);
    Route::post('/links/update/{links}', [\App\Http\Controllers\LinkinsagramController::class, 'update']);

});

