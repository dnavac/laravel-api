<?php

use App\Http\Controllers\studentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/student',[studentController::class,'index']);

Route::post('/student', [studentController::class,'store']);

Route::get('/student/{id}',[studentController::class,'show']);

Route::put('/student/{id}', [studentController::class, 'update']);

Route::delete('/student/{id}',[studentController::class,'destroy']);
