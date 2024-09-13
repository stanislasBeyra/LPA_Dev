<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//enregistrerment des user
Route::post('/register', [AuthController::class,'registeuser']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function () {
    //securite des route privé
    //-----0 usser info session
    Route::get('/user/info', [AuthController::class,'getUserConnectInfo']);
    Route::post('/update/Profile',[AuthController::class,'updateProfile']);
    //-----1 product session

    Route::post('/add/vendor/porduct',[ProductController::class,'AddVendorProduct']);
    Route::post('/update/vendor/products/{produiId}',[ProductController::class,'updateVendorProduct']);
    Route::post('/update/Category',[ProductController::class,'updateCategory']);
    Route::get('/get/vendor/products',[ProductController::class,'getVendorProducts']);
    Route::get('/vendor/products/details/{id}',[ProductController::class,'showPductsDetails']);
    Route::delete('/destroy/Product/{id}',[ProductController::class, 'destroyProduct']);

    // --- 2 category session
    Route::post('/add/vendor/category',[ProductController::class,'addcategory']);
    Route::get('/get/Category',[ProductController::class,'getCategory']);
    Route::put('update/Category',[ProductController::class,'updateCategory']);
    Route::delete('/destroy/Category/{id}',[ProductController::class,'destroyCategory']);

    // --- 3 cart session
    Route::post('/add/cart',[CartController::class,'addToCart']);
    Route::delete('/remove/From/Cart',[CartController::class,'removeFromCart']);

    //--- 4 order session


    Route::post('/deleteAllProducts',[ProductController::class,'deleteAllProducts']);


});
