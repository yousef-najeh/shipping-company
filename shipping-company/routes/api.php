<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('csrf-cookie', function (Request $request) {
//     return response()->json(['csrf_token' => $request->session()->token()]);
// })->middleware('api');

// Route::get('/csrf-token', function (Request $request) {
//     return response() -> json(['csrf_token' => $request -> session() -> token()]);
// }) -> middleware('api');