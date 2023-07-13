<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\InformationController;

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
Route::post('/auth/signup', [AccountController::class, 'signup']);
Route::post('/auth/signin', [AccountController::class, 'signin']);
Route::post('/info/set', [InformationController::class, 'insertOrUpdateInformation']);
Route::get('/info/get', [InformationController::class, 'getInformationByEmail']);





Route::get('/signup', [AccountController::class, 'test']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
