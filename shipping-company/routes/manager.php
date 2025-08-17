<?php


use illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagerController;



Route::get('/manager',[ManagerController::class,'index']);
Route::get('/manager/{id}',[ManagerController::class,'get_by_id']);
Route::post('manager',[ManagerController::class,'store']);
Route::put('/manager/{id}',[ManagerController::class,'update']);
Route::delete('/manager/{id}',[ManagerController::class,'delete']);




