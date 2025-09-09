<?php


use illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


// Route::get('/user',[UserController::class,'index']);
// Route::post('/user',[UserController::class,'store']);
Route::get('/user/{id}',[UserController::class,'get_by_id']);
Route::put('/user/{id}',[UserController::class,'update']);
Route::delete('/user/{id}',[UserController::class,'delete']);


Route::get('/user/role/{role}',[UserController::class,"searchByRole"]);
Route::get('user/name/{name}',[UserController::class,'searchByName']);