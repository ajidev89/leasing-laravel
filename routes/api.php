<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [App\Http\Controllers\Api\UserController::class, 'Register']);
Route::post('/login', [App\Http\Controllers\Api\UserController::class, 'Login']);
Route::post('/forget-password', [App\Http\Controllers\Api\UserController::class, 'ForgetPassword']);
Route::post('/change-password', [App\Http\Controllers\Api\UserController::class, 'ChangePassword']);
Route::group(['middleware' => 'auth:sanctum'], function()
{
    //All the routes that belongs to the group goes here 
    Route::get('/properties', [App\Http\Controllers\Api\PropertyController::class, 'GetAllProperties']);
    Route::get('/add-properties', [App\Http\Controllers\Api\PropertyController::class, 'GetAllProperties']);
    Route::get('/profile', [App\Http\Controllers\Api\UserController::class, 'getUser']);
    Route::get('/logout', [App\Http\Controllers\Api\UserController::class, 'Logout']);

});