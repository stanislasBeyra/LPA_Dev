<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Employee\EmployeeappController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
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
//create role

Route::post('reacterole',[RoleController::class,'reacterole']);

Route::delete('/deleterole/{id}',[RoleController::class,'deleterole']);
Route::put('/restorerole/{id}', [RoleController::class, 'restoreRole']);
Route::delete('/ForcedeleteRole/{id}',[RoleController::class,'ForcedeleteRole']);
Route::get('/get/allrole',[RoleController::class,'getallrole']);

//enregistrerment des user
//Route::post('/register', [AuthController::class,'registeuser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/Clientlogin',[EmployeeappController::class,'Clientlogin']);
Route::delete('/delectuser/{id}',[AuthController::class,'delectuser']);
Route::get('/send-test-email', [EmailController::class, 'sendTestEmail']);
//Employee register
//Route::post('/Employee/Register',[EmployeeController::class,'EmployeeRegister']);
Route::post('/submit/register',[AuthController::class,'SubmitRegister']);

Route::get('urltese', function () {

    return "mise de teste 2024, par Beyra et Gregoire";

});
Route::get('/get/employee',[EmployeeController::class,'getallEmploye']);
Route::get('/get/VendorList',[AuthController::class,'getVendorList']);

Route::post('/products/vendor/', [EmployeeappController::class, 'getProductsByVendorId']);
Route::get('/get/VendorListapp',[EmployeeappController::class,'getVendorListapp']);

Route::middleware('auth:sanctum')->group(function () {
    //get Employyee Info
    Route::get('/get/employee/all',[EmployeeController::class,'getallEmploye']);
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
    Route::delete('/destroy/Product/{ProductId}',[ProductController::class, 'destroyProduct']);

    // --- 2 category session
    Route::post('/add/vendor/category',[ProductController::class,'addcategory']);
    Route::get('/get/Category',[ProductController::class,'getCategory']);
    Route::put('update/Category',[ProductController::class,'updateCategory']);
    Route::delete('/destroy/Category/{id}',[ProductController::class,'destroyCategory']);

    // --- 3 cart session
    Route::post('/add/cart',[CartController::class,'addToCart']);
    Route::get('/get/cart',[CartController::class,'showCartProducts']);
    Route::delete('/remove/From/Cart',[CartController::class,'removeFromCart']);

    //--- 4 order session
    Route::post('/add/order',[OrderController::class,'placeOrder']);
    Route::get('/get/orderproduct',[OrderController::class,'showOrderProducts']);

    //vendor session
    Route::post('/Vendor/validateOrder',[ProductController::class,'VendorvalidateOrder']);
    Route::get('/get/VendorOrders',[ProductController::class,'getVendorOrders']);



    Route::post('/deleteAllProducts',[ProductController::class,'deleteAllProducts']);


});
