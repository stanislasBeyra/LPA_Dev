<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\webLaravel\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('index');
});

// Route::get('/home', function () {
//     return view('homecontent',);
// });


Route::get('viewcategorie',[ProductCategoryController::class,'viecatgeroie'])->name('view.categories');


Route::get('/{page}', [HomeController::class, 'getContent'])->name('content.page');


Route::post('addCategories',[ProductCategoryController::class,'addCategories'])->name('categories.save');
Route::post('/login', [AuthController::class, 'Newlogin'])->name('login');
Route::post('logout',[AuthController::class,'logout'])->name('logout');
