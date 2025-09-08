<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShipmentController;




Route::get('/shipment',[ShipmentController::class,'index']);
Route::get('/shipment/{id}',[ShipmentController::class,'get_by_id']);
Route::post('/shipment',[ShipmentController::class,'store']);
Route::put('/shipment/{id}',[ShipmentController::class,"update"]);
Route::delete('/shipment/{id}',[ShipmentController::class,"delete"]);




