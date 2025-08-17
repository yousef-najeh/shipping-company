<?php

use illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;




Route::get('/admin',[AdminController::class,'index']);
Route::get('/admin/{id}',[AdminController::class,'get_by_id']);
Route::post('/admin',[AdminController::class,'store']);
Route::put('/admin/{id}',[AdminCOntroller::class,"update"]);
Route::delete('/admin/{id}',[AdminController::class,"delete"]);




