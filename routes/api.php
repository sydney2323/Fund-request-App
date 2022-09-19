<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Models\User;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Crud\CrudController;
use App\Http\Controllers\FundRequest\RequestController;
use App\Http\Controllers\FundRequest\BudgetController;
use App\Http\Controllers\FundRequest\CategoryBudgetController;
use App\Http\Controllers\FundRequest\CategoryController;
use App\Http\Controllers\FundRequest\ProjectController;
use App\Http\Controllers\FundRequest\RequestHandlerController;

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

Route::fallback(function(){
    return response()->json([
        'error' => 'URL Not Found.'], 404);
});

//Route::post('/Admin/register',[AuthController::class,'register']);
Route::post('/Admin/login',[AuthController::class,'login']);
Route::get('/Cache/{key}',[AuthController::class,'getCache']);


Route::post('/changing-password',[AuthController::class,'changingPassword']);
Route::post('/forgot-password',[AuthController::class,'forgotPassword']);
Route::post('/reset-password',[AuthController::class,'resetPassword']);

Route::get('/finance/request/{id}',[RequestHandlerController::class,'show']);

Route::middleware(['auth:api', 'role'])->group(function() {


    Route::post('/logout',[AuthController::class,'logout']);

   // Endpoints that can be performed by admin
    Route::middleware(['scope:admin'])->group(function () {    
        Route::post('/Admin/invitation',[AuthController::class,'invitation']);
        Route::post('/Admin/register',[AuthController::class,'register']);
        Route::get('/Admin/activity',[AuthController::class,'activity']);
        Route::apiResource('/Admin/manage-users', CrudController::class);
        Route::apiResource('/Admin/manage-category', CategoryController::class);
        Route::apiResource('/Admin/manage-project', ProjectController::class);
    });

    // Endpoints that can be performed by staff
    Route::middleware(['scope:admin,staff'])->group(function () {
        Route::apiResource('/fund', RequestController::class);

    });

     // Endpoints that can be performed by finance
     Route::middleware(['scope:finance'])->group(function () {
        //request
        Route::get('/finance/request',[RequestHandlerController::class,'index']);
       // Route::get('/finance/request/{id}',[RequestHandlerController::class,'show']);
        Route::patch('/finance/request/accept/{id}',[RequestHandlerController::class,'accept']);
        Route::patch('/finance/request/reject/{id}',[RequestHandlerController::class,'reject']);
        //budget
        Route::apiResource('/finance/budget', BudgetController::class);
        //category-monthly-budget
        Route::apiResource('/finance/budget-category', CategoryBudgetController::class);
        // Route::post('/finance/budget/category',[BudgetController::class,'storeCategoryBudget']);
        // Route::get('/finance/budget/category',[BudgetController::class,'showCategoryBudget']);
        // Route::get('/finance/budget/category/{id}',[BudgetController::class,'showSingleCategoryBudget']);
        // Route::put('/finance/budget/category/{id}',[BudgetController::class,'updateCategoryBudget']);
        // Route::delete('/finance/budget/category/{id}',[BudgetController::class,'deleteCategoryBudget']);

    });

  
});




