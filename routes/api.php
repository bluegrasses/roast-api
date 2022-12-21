<?php

use App\Http\Controllers\BrewMethodsController;
use App\Http\Controllers\CafesController;
use App\Http\Controllers\ProductsController;
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


//保护路由
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
//    cafe
    Route::post('cafes',[CafesController::class,'createCafe']);
    Route::get('cafes',[CafesController::class,'getCafes']);
    Route::get('cafes/{id}',[CafesController::class,'getCafe']);
//brew methods
    Route::get('brew-methods',[BrewMethodsController::class,'getBrewMethods']);

//   Route::get('products',[ProductsController::class,'index']);
});
