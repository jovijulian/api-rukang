<?php

use App\Http\Controllers\Api\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TalentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::controller(RegisterController::class)->group(function () {
            Route::post('/register', 'create');
        });
        Route::controller(LoginController::class)->group(function () {
            Route::delete('/logout', 'logout')->middleware(['auth:api', 'check_role:0,1']);
        });
    });
    Route::prefix('category')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_role:1']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:1']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:1']);
        });
    });
    Route::prefix('talent')->group(function () {
        Route::controller(TalentController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::get('/index-talent-by-category', 'talentByCategory')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_role:1']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:0,1']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:1']);
            Route::post('/update-image-profile/{id}', 'updateImageProfile')->middleware(['auth:api', 'check_role:1']);
        });
    });
    Route::prefix('customer')->group(function () {
        Route::controller(CustomerController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_role:1']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:0,1']);
            // Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:1']);
        });
    });
    Route::prefix('order')->group(function () {
        Route::controller(OrderController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_role:0']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:0,1']);
            // Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:1']);
        });
    });
    Route::prefix('transaction')->group(function () {
        Route::controller(TransactionController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:0,1']);
            Route::post('/create/{status}', 'store')->middleware(['auth:api', 'check_role:0']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:0,1']);
            // Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            Route::get('/index/{customer_id}', 'indexPerUser')->middleware(['auth:api', 'check_role:0']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:1']);
        });
    });
    Route::prefix('review')->group(function () {
        Route::controller(ReviewController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_role:1,2']);
            Route::post('/create/{transaction_id}', 'store')->middleware(['auth:api', 'check_role:0']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_role:0,1,2']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_role:0']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_role:1']);
            Route::get('/index-review-talent', 'indexPerTalent')->middleware(['auth:api', 'check_role:1,2']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_role:0']);
        });
    });
    // Route::prefix('user')->group(function () {
    //     Route::controller(UserController::class)->group(function () {
    //         Route::post('/reset-password', 'resetPassword');
    //         Route::get('/user-inactive', 'getUserInactive')->middleware(['auth:api', 'check_admin:1,5']);
    //         Route::put('/update-status-user/{id}', 'setActiveUser')->middleware(['auth:api', 'check_admin:1']);
    //         Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,5']);
    //         Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1']);
    //         Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,5']);
    //         Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1']);
    //         Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1']);
    //         Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,5']);
    //         Route::post('/datatable-inactive', 'datatableInactive')->middleware(['auth:api', 'check_admin:1,5']);
    //     });
    // });
});