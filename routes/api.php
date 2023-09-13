<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DescriptionController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ShippingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SegmentController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\ModuleController;

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
            Route::post('/forgot-password', 'forgotPassword');
        });
        Route::controller(LoginController::class)->group(function () {
            Route::delete('/logout', 'logout')->middleware('auth:api');
        });
    });
    Route::prefix('user')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('/reset-password', 'resetPassword');
            Route::get('/user-inactive', 'getUserInactive')->middleware(['auth:api', 'check_admin']);
            Route::put('/update-status-user/{id}', 'setActiveUser')->middleware(['auth:api', 'check_admin']);
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin']);
            Route::post('/datatable-inactive', 'datatableInactive')->middleware(['auth:api', 'check_admin']);
        });
    });
    Route::prefix('group')->group(function () {
        Route::controller(GroupController::class)->group(function () {
            Route::get('/group', 'getGroups');
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin']);
        });
    });
    Route::prefix('segment')->group(function () {
        Route::controller(SegmentController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('description')->group(function () {
        Route::controller(DescriptionController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('category')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('status')->group(function () {
        Route::controller(StatusController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('product')->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/update-status/{id}', 'setStatusLogProduct')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('shipping')->group(function () {
        Route::controller(ShippingController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
    Route::prefix('module')->group(function () {
        Route::controller(ModuleController::class)->group(function () {
            Route::get('/index', 'index')->middleware('auth:api');
            Route::post('/create', 'store')->middleware('auth:api');
            Route::get('/detail/{id}', 'show')->middleware('auth:api');
            Route::put('/update/{id}', 'update')->middleware('auth:api');
            Route::delete('/delete/{id}', 'destroy')->middleware('auth:api');
            Route::post('/datatable', 'datatable')->middleware('auth:api');
        });
    });
});
