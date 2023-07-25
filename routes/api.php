<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\Api\PaymentController;


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

//Route::get('/info', [InformationController::class, 'getInformationByEmail']);

Route::group(['middleware' => 'token.validate'], function () {
    Route::post('/info/me', [InformationController::class, 'insertOrUpdateInformation']);
    Route::get('/info/me', [InformationController::class, 'getInformationByEmail']);



    Route::post('/info/friend', [InformationController::class, 'insertFriendsInformation']);
    Route::get('/info/friend', [InformationController::class, 'viewFriendsInformationByEmail']);


    Route::delete('/info/friend', [InformationController::class, 'deleteFriendsInformationByIdCard']);
});


//Route::get('/payment', [PaymentController::class, 'index']);
//Route::post('/payment', [PaymentController::class, 'store']);

Route::apiResource('/payment', PaymentController::class);


Route::post('/test', [AccountController::class, 'test']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
