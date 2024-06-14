<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\EverydayController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AnalyticController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/volunteers', VolunteerController::class);
// Route::get('/volunteers',[VolunteerController::class,'index']);
// Route::post('/volunteers',[VolunteerController::class,'store']);
// Route::put('/volunteers/{volunteer}',[VolunteerController::class,'update']);
// Route::delete('/volunteers/{volunteer}',[VolunteerController::class,'destroy']);
Route::apiResource('/meals', MealController::class);
// Route::get('/meals',[MealController::class,'index']);
// Route::post('/meals',[MealController::class,'store']);
// Route::get('/meals/{meal}',[MealController::class,'show']);
// Route::put('/meals/{meal}',[MealController::class,'update']);
Route::apiResource('/customs', CustomController::class);
// Route::get('/customs',[CustomController::class,'index']);
// Route::post('/customs',[CustomController::class,'store']);
// Route::get('/customs/{user_id}',[CustomController::class,'show']);
// Route::put('/customs/{custom}',[CustomController::class,'update']);
// Route::delete('/customs/{custom}',[CustomController::class,'destroy']);
Route::apiResource('/everydays', EverydayController::class);
Route::post('/everydays/{user_id}', [EverydayController::class, 'saveToAnalytics']);
// Route::get('/everydays',[EverydayController::class,'index']);
// Route::post('/everydays',[EverydayController::class,'store']);
// Route::get('/everydays/{user_id}',[EverydayController::class,'show']);
// Route::delete('/everydays/{user_id}',[EverydayController::class,'destroy']);
// Route::post('/everydays/{user_id}',[EverydayController::class,'saveToAnalytics']);
Route::apiResource('/analytics', AnalyticController::class);
// Route::get('/analytics',[AnalyticController::class,'index']);
// Route::post('/analytics',[AnalyticController::class,'store']);
// Route::get('/analytics/{user_id}',[AnalyticController::class,'show']);
// Route::delete('/analytics/{user_id}',[AnalyticController::class,'destroy']);