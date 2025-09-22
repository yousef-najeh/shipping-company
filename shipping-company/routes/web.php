<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/testing', function () {
    return response()->json(['message' => 'Testing route is working!']);
});



Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/login', 'login')->name('login'); 
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});


Route :: middleware('auth')->group(function () {
    Route::get('/shop',[VendorShopController::class,'index']);
    Route::get('/shop/{id}',[VendorShopController::class,'get_by_id']);
    Route::post('/shop',[VendorShopController::class,'store']);
    Route::put('/shop/{id}',[VendorShopController::class,'update']);
    Route::delete('/shop/{id}',[VendorShopController::class,'destroy']);
});


Route::middleware('auth')->group(function () {
    Route::get('/user',[UserController::class,'index']);
    Route::post('/user',[UserController::class,'store']);
    Route::get('/user/{id}',[UserController::class,'get_by_id']);
    Route::put('/user/{id}',[UserController::class,'update']);
    Route::delete('/user/{id}',[UserController::class,'delete']);
});


Route :: middleware('auth')->group(function () {
    Route::get('/shipment',[ShipmentController::class,'index']);
    Route::get('/shipment/{id}',[ShipmentController::class,'get_by_id']);
    Route::post('/shipment',[ShipmentController::class,'store']);
    Route::put('/shipment/{id}',[ShipmentController::class,"update"]);
    Route::delete('/shipment/{id}',[ShipmentController::class,"delete"]);
});


Route :: middleware('auth')->group(function () {
    Route::get('/order',[OrderController::class,'index']);
    Route::get('/order/{id}',[OrderController::class,'get_by_id']);
    Route::post('/order',[OrderController::class,'store']);
    Route::put('/order/{id}',[OrderController::class,"update"]);
    Route::delete('/order/{id}',[OrderController::class,"delete"]);
});



Route :: middleware('auth')->group(function () {
    Route::get('/manager',[ManagerController::class,'index']);
    Route::get('/manager/{id}',[ManagerController::class,'get_by_id']);
    Route::post('manager',[ManagerController::class,'store']);
    Route::put('/manager/{id}',[ManagerController::class,'update']);
    Route::delete('/manager/{id}',[ManagerController::class,'delete']);
});


Route::middleware('auth')->group(function () {
    Route::get('/item', [ItemController::class, 'index']);
    Route::post('/item', [ItemController::class, 'store']);
    Route::get('/item/{id}', [ItemController::class, 'get_by_id']);
    Route::put('/item/{id}', [ItemController::class, 'update']);
    Route::delete('/item/{id}', [ItemController::class, 'destroy']);
});



Route :: middleware('auth')->group(function () {
    Route::get('/employee',[EmployeeController::class,'index']);
    Route::get('/employee/{id}',[EmployeeController::class,'get_by_id']);
    Route::post('employee',[EmployeeController::class,'store']);
    Route::put('/employee/{id}',[EmployeeController::class,'update']);
    Route::delete('/employee/{id}',[EmployeeController::class,'delete']);
});


Route :: middleware('auth')->group(function () {
    Route::get('/driver',[DriverController::class,'index']);
    Route::get('/driver/{id}',[DriverController::class,'get_by_id']);
    Route::post('driver',[DriverController::class,'store']);
    Route::put('/driver/{id}',[DriverController::class,'update']);
    Route::delete('/driver/{id}',[DriverController::class,'delete']);
});



Route ::middleware('auth')->group(function () {
    Route::get('/client',[ClientController::class,'index']);
    Route::get('/client/{id}',[ClientController::class,'get_by_id']);
    Route::post('/client',[ClientController::class,'store']);
    Route::put('/client/{id}',[ClientController::class,"update"]);
    Route::delete('/client/{id}',[ClientController::class,"delete"]);
});


Route :: middleware('auth')->group(function () {
    Route::get('/admin',[AdminController::class,'index']);
    Route::get('/admin/{id}',[AdminController::class,'show']);
    Route::post('/admin',[AdminController::class,'store']);
    Route::put('/admin/{id}',[AdminCOntroller::class,"update"]);
    Route::delete('/admin/{id}',[AdminController::class,"destroy"]);
});













