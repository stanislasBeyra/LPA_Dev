<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
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

// Route pour la page d'accueil, protégée par le middleware 'auth:web'
Route::middleware('auth:web')->group(function () {
    Route::get('/', function () {
        return view('index');
    });

    // delete categorie
    Route::post('deletecategorie',[ProductCategoryController::class,'deleteCategories'])->name('delete.categorie');

    // Route::get('/{page}', [HomeController::class, 'getContent'])->name('content.page');
    Route::get('/{page}', [HomeController::class, 'getContent'])->name('content.page')
         ->where('page', '^(?!login$|register$).*');

    //update 2024/11/22 01:17 PM

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('employee/register', 'EmployeeRegister')->name('employee.register');

    });

});

// Route de connexion, accessible uniquement pour les utilisateurs non authentifiés
Route::middleware('guest')->group(function () {
    Route::get('login', [HomeController::class, 'loginform'])->name('login');
    Route::post('/login', [AuthController::class, 'Newlogin'])->name('login.post');
});

// Déconnexion
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Routes publiques
Route::post('addCategories', [ProductCategoryController::class, 'addCategories'])->name('categories.save');
