<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
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


#------------------ Job Applicant Identity Routes ---------------
Route::group([

   // 'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/createuser', [AuthController::class, 'createuser']);
    Route::get('/findemail/{email}', [AuthController::class, 'FindEmail']);
    Route::get('/findusername/{name}', [AuthController::class, 'FindUsername']);

});

Route::group([

    'middleware' => 'jwt.verify:user',
     'prefix' => 'account'

 ], function ($router) {
     Route::get('/getallusers', [AccountController::class, 'getallusers']);
     Route::get('/getuserbyid/{id}', [AccountController::class, 'getuser']);
     Route::get('/getallwallet', [AccountController::class, 'getallwallet']);
     Route::get('/getwalletbyid/{id}', [AccountController::class, 'getwallet']);
     Route::get('/count', [AccountController::class, 'count']);
     Route::post('/sendmoney', [AccountController::class, 'send']);
     Route::post('/upload', [AccountController::class, 'upload']);
     Route::post('/create-wallet', [AccountController::class, 'createwallet']);

 });




