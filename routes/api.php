<?php

use App\Http\Controllers\AgenceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Employee\EmployeeappController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Paytou\apitesteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\webLaravel\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//create role

Route::get('getUsersWithVendors',[HomeController::class,'getUsersWithVendors']);
Route::post('reacterole',[RoleController::class,'reacterole']);

Route::delete('/deleterole/{id}',[RoleController::class,'deleterole']);
Route::put('/restorerole/{id}', [RoleController::class, 'restoreRole']);
Route::delete('/ForcedeleteRole/{id}',[RoleController::class,'ForcedeleteRole']);
Route::get('/get/allrole',[RoleController::class,'getallrole']);

//enregistrerment des user
//Route::post('/register', [AuthController::class,'registeuser']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/Clientlogin',[EmployeeappController::class,'Clientlogin']);
Route::post('/otp/verification',[EmployeeappController::class,'OtpVerification']);
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

Route::get('/all/app/product', [EmployeeappController::class, 'appAllproduct']);
Route::post('Admin/update/Customer/info',[AuthController::class,'AdminupdateCustomerinfo']);
Route::post('Admin/update/vendor/info',[AuthController::class,'Adminupdatevendorinfo']);
// recuperer les methodes de paiement
Route::get('getCutomerPaiement',[SalaryController::class,'getCutomerPaiement']);

 Route::get('/get/employee/all',[EmployeeController::class,'getallEmploye']);

 Route::get('/get/All/Agences/Info',[AgenceController::class,'getAllAgencesInfo']);

 Route::post('/createAgence',[AgenceController::class,'createAgence']);
 Route::post('/editAgence',[AgenceController::class,'editAgence']);
 Route::post('deleteAgence',[AgenceController::class,'deleteAgence']);
 Route::post('reset',[AgenceController::class,'reset']);

Route::middleware('auth:sanctum')->group(function () {
    // agences  info
    Route::post('/save-token', [NotificationController::class, 'saveToken']);

    //get Employyee Info
   
    //securite des route privé
    //-----0 usser info session
    Route::get('/user/info', [AuthController::class,'getUserConnectInfo']);
    Route::get('/get/Count/user',[AuthController::class,'getCountUser']);
    Route::post('/update/Profile',[AuthController::class,'updateProfile']);
    Route::post('/update/User/Password/',[AuthController::class,'updateUserPassword']);
    //-----1 product session

    Route::post('/add/vendor/porduct',[ProductController::class,'AddVendorProduct']);
    Route::post('/update/vendor/products/{produiId}',[ProductController::class,'updateVendorProduct']);
    Route::post('/update/Category',[ProductController::class,'updateCategory']);
    Route::get('/get/vendor/products',[ProductController::class,'getVendorProducts']);
    Route::get('/vendor/products/details/{id}',[ProductController::class,'showPductsDetails']);
    Route::delete('/destroy/Product/{ProductId}',[ProductController::class, 'destroyProduct']);

    // --- 2 category session
    Route::post('/add/vendor/category',[ProductController::class,'addcategory']);
    Route::get('/get/category/foradmin/',[ProductController::class,'getCategoryforadmin']);
    Route::post('update/Category',[ProductController::class,'updateCategory']);
    Route::post('/destroy/Category/',[ProductController::class,'destroyCategory']);

    // --- 3 cart session
    Route::post('/add/cart',[CartController::class,'addToCart']);
    Route::get('/get/cart',[CartController::class,'showCartProducts']);
    Route::delete('/remove/From/Cart',[CartController::class,'removeFromCart']);

    //--- 4 order sessionb lastplaceOrder
    Route::post('/add/order',[OrderController::class,'lastplaceOrder']);
    Route::post('/add/new/order',[OrderController::class,'placeOrder']);



    Route::get('/get/orderproduct',[OrderController::class,'showOrderProducts']);
    Route::get('/get/new/orderproduct',[OrderController::class,'newshowOrderProducts']);

    //vendor session
    Route::post('/Vendor/validateOrder',[ProductController::class,'VendorvalidateOrder']);
    Route::get('/get/VendorOrders',[ProductController::class,'getVendorOrders']);
    Route::get('/fetchAll/Vendor/Products',[ProductController::class,'fetchAllVendorProducts']);




    Route::post('/deleteAllProducts',[ProductController::class,'deleteAllProducts']);
    Route::post('/set/mode/payement',[ProductController::class,'createPayment']);
    //admin
    Route::post('/valided/customer/order',[SalaryController::class,'Rhvalidatedorder']);

});

// getbanner
Route::get('getbanners',[BannerController::class,'getBannerforEmployee']);
Route::get('/products/search', [ProductController::class, 'search']);


Route::get('getallvendororder',[OrderController::class,'getallvendororder']);


Route::get('getordersall',[OrderController::class,'getorders']);



Route::get('/get/Category',[ProductController::class,'getCategory']);
//travail de restant
Route::post('assignMinimumSalary',[SalaryController::class,'assignMinimumSalary']);
Route::post('payement',[ProductController::class,'createPayment']);
Route::get('Rhvalidatedorder',[SalaryController::class,'Rhvalidatedorder']);

Route::get('getAllOrders',[SalaryController::class,'getAllOrders']);
Route::get('getProductsByCategory/{id}',[ProductController::class,'getProductsByCategory']);




//paytou teste
Route::get('testeapi',[apitesteController::class,'testeapi']);
Route::post('paytou/user',[apitesteController::class,'getUser']);
Route::post('/payfrais/dossier',[apitesteController::class,'fraisdossier']);
Route::get('getHistoriqueCommission',[apitesteController::class,'getHistoriqueCommission']);
