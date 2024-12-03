<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserDeactivationReasonController;
use App\Http\Controllers\VendorController;
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

    //add admin
    Route::post('/add/admins',[AdminController::class,'addAdmin'])->name('add.admins');
    Route::post('/delete/admin',[AdminController::class,'deleteAdmin'])->name('delete.admin');
    Route::post('/updateAdmin',[AdminController::class,'updateAdmin'])->name('update.admins');
    //roles info

    Route::post('/add/roles', [RoleController::class, 'addroles'])->name('roles.add');
    Route::post('/roles/update', [RoleController::class, 'updateRole'])->name('roles.update');
    Route::post('/roles/delete', [RoleController::class, 'deleteRoles'])->name('roles.delete');

    // update User Auth avatar
    Route::post('/profile/update-avatar', [VendorController::class, 'updatevendorlogo'])->name('profile.updateAvatar');
    Route::post('/profile/update-password', [VendorController::class, 'UpdateVendorPassword'])->name('profile.updatePassword');
    Route::post('/admin/vendor/update-avatar', [VendorController::class, 'AdminChangeVendorAvatar'])->name('admin.vendor.update-avatar');

    // agencies 
    Route::post('/createAgencies', [AgenceController::class, 'createAgencies'])->name('create.agencies');
    Route::post('/deleteagence', [AgenceController::class, 'deleteAgencies'])->name('agences.delete');
    // Route pour modifier une agence
    Route::post('/edit', [AgenceController::class, 'editAgencies'])->name('agences.edit');
    // delete categorie
    Route::post('deletecategorie', [ProductCategoryController::class, 'deleteCategories'])->name('delete.categorie');
    Route::post('updateCategories', [ProductCategoryController::class, 'updateCategories'])->name('update.Categorie');

    // vendor
    Route::post('deleteVendors', [VendorController::class, 'deleteVendors'])->name('delete.Vendor');

    // In routes/web.php
    Route::get('/vendors-detail/{id}', [VendorController::class, 'getUsersWithVendors'])->name('users.vendors');
    Route::post('/vendor/reset-password', [VendorController::class, 'resetVendorPassword'])->name('vendor.resetPassword');

    //Banner
    Route::post('storeBanner',[BannerController::class,'storeBanner'])->name('banners.store');
    Route::post('/banner/change/status', [BannerController::class, 'desactivebanner'])->name('banner.status.update');
    Route::post('/DeleteBanner',[BannerController::class,'DeleteBanner'])->name('delete.banner');
    //product
    Route::post('/vendor/product/store', [ProductController::class, 'storevendorproduct'])->name('vendor.product.store');
    Route::post('NewvendorvalidateOrder', [OrderController::class, 'NewvendorvalidateOrder'])->name('validated.order');
    Route::post('NewAdminVendorValidateOrder', [OrderController::class, 'NewAdminVendorValidateOrder'])->name('AdminVendor.ValidateOrder');

    //employee
    Route::post('employee/register', [EmployeeController::class, 'RegisterEmplyees'])->name('employee.register');
    Route::post('/employee/update', [EmployeeController::class, 'updateEmployesInfo'])->name('employee.update');
    Route::post('/deleteEmployee',[EmployeeController::class,'deleteEmployee'])->name('delete.employee');
    Route::post('/deactivateemployee',[UserDeactivationReasonController::class,'deactivateEmployee'])->name('deactivate.employee');

    Route::get('getallvendorcoonectproduct', [ProductController::class, 'getallvendorcoonectproduct']);
    Route::get('getorders', [OrderController::class, 'getorders']);
    Route::get('NewvendorvalidateOrder', [OrderController::class, 'NewvendorvalidateOrder']);
    Route::get('getNewallvendorProducts', [ProductController::class, 'getNewallvendorProducts']);
    Route::get('admingetvendororder', [OrderController::class, 'admingetvendororder']);
    Route::get('getEmployeeListe', [EmployeeController::class, 'getEmployeeListe']);


    Route::post('/delete/vendor/Product', [ProductController::class, 'newdeletevendorProduct'])->name('delete.product');
    Route::post("/update/vendor/product", [ProductController::class, 'NewupdateVendorProduct'])->name('update.product');
    //admin
    Route::post('/admin/delete/Vendor/Product', [ProductController::class, 'admindeleteVendorProduct'])->name('delete.VendorProduct');


    // Route::get('/{page}', [HomeController::class, 'getContent'])->name('content.page');
    Route::get('/{page}', [HomeController::class, 'getContent'])->name('content.page')
        ->where('page', '^(?!login$|register$).*');
});

Route::post('/register/vendor', [AuthController::class, 'vendorgister'])->name('vendor.register');
Route::post('/VendorinfoUpdate', [VendorController::class, 'VendorinfoUpdate'])->name('Vendor.Update');
// Route de connexion, accessible uniquement pour les utilisateurs non authentifiés
Route::middleware('guest')->group(function () {
    Route::get('login', [HomeController::class, 'loginform'])->name('login');
    Route::post('/login', [AuthController::class, 'Newlogin'])->name('login.post');
});

// Déconnexion
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Routes publiques
Route::post('addCategories', [ProductCategoryController::class, 'addCategories'])->name('categories.save');
