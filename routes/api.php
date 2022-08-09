<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Models\User;
use App\Http\Controllers\Auth\AuthController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test',[TestController::class,'test']);

// Phase 2 Authentication Endpoints
Route::post('/Admin/register',[AuthController::class,'register']);
Route::post('/Admin/invitation',[AuthController::class,'invitation']);
Route::post('/Admin/changing-password',[AuthController::class,'changingPassword']);

Route::post('/Admin/forgot-password',[AuthController::class,'forgotPassword']);
Route::post('/Admin/reset-password',[AuthController::class,'resetPassword']);

//Route::post('/Admin/register',[AuthController::class,'register']);
Route::post('/Admin/login',[AuthController::class,'login']);

Route::middleware(['auth:api', 'role'])->group(function() {

    // register users
    Route::middleware(['scope:staff'])->group(function () {



        Route::post('/Admin/register',[AuthController::class,'register']);

        //Route::middleware(['auth:api','scope:admin'])->post('/Admin/register',[AuthController::class,'register']);

        // Route::middleware(['scope:admin,moderator'])->post('/user', function(Request $request) {

        //     return User::create($request->all());
        // });

    });

  
});




