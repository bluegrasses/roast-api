<?php

use App\Http\Controllers\BrewMethodsController;
use App\Http\Controllers\CafesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

//公开路由
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::get('/tags',[TagsController::class,'getTags']);

//保护路由
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
//    cafe
    Route::post('cafes',[CafesController::class,'postNewCafe']);
    Route::get('cafes',[CafesController::class,'getCafes']);
    Route::get('cafes/{id}',[CafesController::class,'getCafe']);
//brew methods
    Route::get('brew-methods',[BrewMethodsController::class,'getBrewMethods']);
//cafe
    Route::post('/cafes/{id}/like',[CafesController::class,'postLikeCafe']);
    Route::delete('/cafes/{id}/like',[CafesController::class,'deleteLikeCafe']);
//    用户为某个咖啡店添加咖啡
    Route::post('/cafes/{id}/tags',[CafesController::class,'postAddTags']);
    Route::delete('/cafes/{id}/tags/{tagID}',[CafesController::class,'deleteCafeTag']);

});
