<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\UserController;
use App\Http\Controllers\SensorController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user api endpoint
Route::post('/login',[UserController::class,'authenticate']);
Route::get('/users',[UserController::class,'getData']);
Route::post('/users',[UserController::class,'store']);
Route::delete('/users/{id}',[UserController::class,'remove']);


//Branch api endpoint
Route::post('/sensorA',[SensorController::class,'storeSensorDataA']);
Route::post('/sensorB',[SensorController::class,'storeSensorDataB']);

//volume
Route::get('/volumeA',[SensorController::class,'getVolumeA']);
Route::get('/volumeB',[SensorController::class,'getVolumeB']);

//leakage
Route::get('/leakage',[SensorController::class,'getLeakage']);

//Dashboard
Route::get('/dashboard',[SensorController::class,'getData']);
