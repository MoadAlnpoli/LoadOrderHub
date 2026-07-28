<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('tasks', [TaskController::class,'index']);

// Route::post('tasks', [TaskController::class,'store']);

// Route::put('tasks/{id}', [TaskController::class,'update']);

// Route::get('tasks/{id}', [TaskController::class,'show']);

// Route::delete('tasks/{id}', [TaskController::class,'destroy']);
Route::apiResource('tasks', TaskController::class);
Route::apiResource('Profile', ProfileController::class);
Route::post('user/{id}/profile',[userController::class,'show']);



