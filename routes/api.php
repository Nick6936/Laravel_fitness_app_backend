<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\EverydayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalyticController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
//JW token is used insted of sanctum for authentication

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    
});


Route::middleware(['auth:api'])->group(function(){
    Route::post('me', [AuthController::class,'me']);
    Route::post('update', [AuthController::class,'update']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('delete', [AuthController::class,'deleteAccount']);
    Route::post('userid', [AuthController::class,'getAllUserIds']);
});




//this route is only useful for fetching user data and id nothing else
Route::apiResource('/users', UserController::class);
//Route::get('/userid',[UserController::class,'getAllUserIds']);
// Route::get('/users',[UserController::class,'index']);

Route::apiResource('/meals', MealController::class);
 Route::get('/meals/{search_key}',[MealController::class,'index']);
// Route::post('/meals',[MealController::class,'store']);
// Route::get('/meals/{meal}',[MealController::class,'show']);
// Route::put('/meals/{meal}',[MealController::class,'update']);
Route::apiResource('/customs', CustomController::class);
// Route::get('/customs',[CustomController::class,'index']);
// Route::post('/customs',[CustomController::class,'store']);
// Route::get('/customs/{user_id }',[CustomController::class,'show']);
// Route::put('/customs/{custom}',[CustomController::class,'update']);
// Route::delete('/customs/{custom}',[CustomController::class,'destroy']);


Route::apiResource('/everydays', EverydayController::class);
//created two method for simillar things but, saveAnalyticsAndClearMeals will do it for all the users and saveToAnalytics will do it for specific user. 
//saveAnalyticsAndClearMeals was made to be auto executed whereas saveToAnalytics is triggered manually, or from front end iykyk
//Route::post('/everydays/magic', [EverydayController::class, 'saveAnalyticsAndClearMeals']);//will gather all the user_id and perform operation for all of them, no need to pass any parameter
Route::post('/everydays/{user_id}', [EverydayController::class, 'saveToAnalytics']);//will save the sum of data for provided user to analytics and delete the user specific meals in everyday table
Route::get('/everydays/home/{user_id}',[EverydayController::class,'getHome']);
Route::delete('/ everydays/destroy/{user_id}', [EverydayController::class, 'destroyAll']);//for deleting all the user specific meals in everyday table
// Route::get('/everydays',[EverydayController::class,'index']);
// Route::post('/everydays',[EverydayController::class,'store']);
// Route::get('/everydays/{user_id}',[EverydayController::class,'show']);
// Route::delete('/everydays/{everydayid}',[EverydayController::class,'destroy']);//for deleting specific meal in everyday table

 
Route::apiResource('/analytics', AnalyticController::class);
//Route::get('/analytics', [AnalyticController::class, 'index']);
// Route::post('/analytics',[AnalyticController::class,'store']);
// Route::get('/analytics/{user_id}',[AnalyticController::class,'show']);
// Route::delete('/analytics/{user_id}',[AnalyticController::class,'destroy']);