<?php

use App\Events\MyEvent;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserDeactivationReasonController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\webLaravel\HomeController;
use Illuminate\Support\Facades\Request;
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
// tester pusher
// Route::post('/send-event', function (Request $request) {
//     $message = $request->input('message');
    
//     event(new MyEvent($message));
    
//     return response()->json(['status' => 'Événement diffusé']);
// });

// Route::post('/send-event', function (Request $request) {
//     $message = $request->input('message');
    
//     event(new MyEvent($message));
    
//     return response()->json(['status' => 'Événement diffusé']);
// })->name('send.event');

// In routes/web.php
// Route::middleware(['web'])->group(function () {
//     Route::post('/send-event', function (Request $request) {
//         $message = $request->input('message');
        
//         event(new MyEvent($message));
        
//         return response()->json(['status' => 'Événement diffusé']);
//     })->name('send.event');
// });

Route::get('/pusher', function () {
    return view('testePusher'); // Retourne la vue Blade 'myview'
});

Route::get('/send-event', [HomeController::class, 'sendEvent'])->name('send.event');

//fin du teste du pusher
Route::get('/addOrderCodeToOrder',[OrderController::class,'addOrderCodeToOrder']);

// Route pour la page d'accueil, protégée par le middleware 'auth:web'
Route::middleware('auth:web')->group(function () {
    // Route::get('/', function () {
    //     return view('index');
    // });
    // Route::post('/save-token',[NotificationController::class,'saveToken']);
    Route::post('/save-token', [NotificationController::class, 'saveToken']);

    Route::get('/',[AdminController::class,'index'])->name('index.home');

    //add admin
    Route::post('/add/admins',[AdminController::class,'addAdmin'])->name('add.admins');
    Route::post('/delete/admin',[AdminController::class,'deleteAdmin'])->name('delete.admin');
    Route::post('/updateAdmin',[AdminController::class,'updateAdmin'])->name('update.admins');

    Route::get('/admin/search', [AdminController::class, 'searchAdmin'])->name('search.admin');
    //roles info

    Route::post('/add/roles', [RoleController::class, 'addroles'])->name('roles.add');
    Route::post('/roles/update', [RoleController::class, 'updateRole'])->name('roles.update');
    Route::post('/roles/delete', [RoleController::class, 'deleteRoles'])->name('roles.delete');
    Route::get('/searchRole',[RoleController::class,'searchRole'])->name('search.role');

    // update User Auth avatar
    Route::post('/profile/update-avatar', [VendorController::class, 'updatevendorlogo'])->name('profile.updateAvatar');
    Route::post('/profile/update-password', [VendorController::class, 'UpdateVendorPassword'])->name('profile.updatePassword');
    Route::post('/admin/vendor/update-avatar', [VendorController::class, 'AdminChangeVendorAvatar'])->name('admin.vendor.update-avatar');
    Route::get('/search/users/vendors',[VendorController::class,'searchvendors'])->name('search.vendor');
    Route::post('UpdatevendorInfo',[VendorController::class,'UpdatevendorInfo'])->name('update.vendor.info');

    // agencies 
    Route::post('/createAgencies', [AgenceController::class, 'createAgencies'])->name('create.agencies');
    Route::post('/deleteagence', [AgenceController::class, 'deleteAgencies'])->name('agences.delete');
    // Route pour modifier une agence
    Route::post('/edit', [AgenceController::class, 'editAgencies'])->name('agences.edit');
    Route::get('/search/agences',[AgenceController::class,'searchAgences'])->name('search.agences');
    // delete categorie
    Route::post('deletecategorie', [ProductCategoryController::class, 'deleteCategories'])->name('delete.categorie');
    Route::post('updateCategories', [ProductCategoryController::class, 'updateCategories'])->name('update.Categorie');
    Route::get('/search/product/category', [ProductCategoryController::class, 'searchproductcategory'])->name('search.category');

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
    Route::get('/search/order',[OrderController::class,'searchOrder'])->name('search.order');
    Route::get('/orders/search', [OrderController::class, 'SearchgetOrders'])->name('vendororders.search');
    //count orders
    Route::get('countadminorder',[OrderController::class,'countadminorder'])->name('count.adminorder');
    Route::get('countvendororder',[OrderController::class,'countvendororder'])->name('countvendor.order');

    //employee
    Route::post('employee/register', [EmployeeController::class, 'RegisterEmplyees'])->name('employee.register');
    Route::post('/employee/update', [EmployeeController::class, 'updateEmployesInfo'])->name('employee.update');
    Route::post('/deleteEmployee',[EmployeeController::class,'deleteEmployee'])->name('delete.employee');
    Route::get('/Search/employee',[EmployeeController::class,'SearchEmployee'])->name('Search.employee');
    // Route::get('/employees-detail/${id}',[EmployeeController::class,'getEmployeeDetaill'])->name('employee.detail');
    Route::get('/employees-detail/{id}', [EmployeeController::class, 'getEmployeeDetaill'])->name('employees.detail');
    Route::post('/UpdateEmployeeInfo',[EmployeeController::class,'UpdateEmployeeInfo'])->name('update.employee.info');

    
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

    Route::get('/search/vendor/product',[ProductController::class,'searchvendorproduct'])->name('searvendor.product');

    Route::get('/getNewallvendorProductsajax',[ProductController::class,'getNewallvendorProductsajax'])->name('admin.vendorproduct');


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
