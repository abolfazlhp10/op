<?php

//header('Access-Control-Allow-Origin:*');

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use Illuminate\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ConttactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;
use Illuminate\Contracts\Cache\Store;
use App\Http\Controllers\ProjectteamController;
use App\Http\Controllers\CommentsController;


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


// Route::middleware(['auth:sanctum'])->group(function(){

    /*MENUS*/

    Route::get('/menus/showclint', [\App\Http\Controllers\MenuController::class, 'showclint']);


    /*CARTS*/

    Route::get('/carts/show', [\App\Http\Controllers\CartController::class, 'show']);


    /*CONTACT*/

    Route::post('/sendemail', [\App\Http\Controllers\ConttactController::class, 'sendemail']);

    /*LOGOS*/


    Route::get('/logos/show', [\App\Http\Controllers\LogoController::class, 'show']);

    /*LINKINSAGRAM*/


    Route::get('/links/show', [\App\Http\Controllers\LinkinsagramController::class, 'show']);

    /*orders*/
    Route::post('/orders/{userid}/store/', [\App\Http\Controllers\OrderController::class, 'store']);

    Route::get('/orders/{userid}/showall', [\App\Http\Controllers\OrderController::class, 'getAllOrders']);

    Route::get('/orders/{userid}/order-count', [\App\Http\Controllers\OrderController::class, 'getOrderCount']);

// });

// Route::middleware(['admin','auth:sanctum'])->group(function(){
    Route::post('/menus/store', [MenuController::class, 'store']);
    Route::post('/menus/update/{menu}', [\App\Http\Controllers\MenuController::class, 'update']);
    Route::get('/menus/showadmin', [\App\Http\Controllers\MenuController::class, 'showadmin']);
    Route::delete('/menus/destroy/{menu}', [\App\Http\Controllers\MenuController::class, 'destroy']);

    Route::post('/carts/store', [\App\Http\Controllers\CartController::class, 'store']);
    Route::put('/carts/update/{cart}', [\App\Http\Controllers\CartController::class, 'update']);
    Route::delete('/carts/destroy/{cart}', [\App\Http\Controllers\CartController::class, 'destroy']);
    Route::get('cart/{id}',[\App\Http\Controllers\CartController::class,'showOneCart']);

    Route::post('/logos/store', [\App\Http\Controllers\LogoController::class, 'store']);
    Route::post('/logos/update/{logo}', [\App\Http\Controllers\LogoController::class, 'update']);

    Route::post('/links/store', [\App\Http\Controllers\LinkinsagramController::class, 'store']);
    Route::post('/links/update/{links}', [\App\Http\Controllers\LinkinsagramController::class, 'update']);

    Route::get('show-users',[UsersController::class,'showAllUsers']);

    Route::get('orders',[\App\Http\Controllers\OrderController::class,'index']);

	/*articles*/

    Route::post('/articles/', [\App\Http\Controllers\ArticleController::class, 'store']);
    Route::post('/articles/update/{article}', [\App\Http\Controllers\ArticleController::class, 'update']);
    Route::get('/articles/show', [\App\Http\Controllers\ArticleController::class, 'show']);
    Route::delete('/articles/destroy/{article}', [\App\Http\Controllers\ArticleController::class, 'destroy']);

    // comments

    //store comment
    Route::post('comments/',[CommentsController::class,'store']);

    //change comment status
    Route::post('comments/{comment_id}/changeStatus',[CommentsController::class,'changeStatus']);

    //update commment
    Route::put('comments/{comment_id}',[CommentsController::class,'update']);

    //delete comment
    Route::delete('comments/{comment_id}',[CommentsController::class,'destroy']);

    //show all comments related to the article
    Route::get('comments/{article_id}',[CommentsController::class,'show']);

    //show all comments in admin panel
    Route::get('admin/comments/',[CommentsController::class,'showAllComments']);

    //admin reply to user
    Route::post('admin/replyToComment/{comment_id}',[CommentsController::class,'reply']);



// });



