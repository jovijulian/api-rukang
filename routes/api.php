<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\SeederProductController;
use App\Http\Controllers\Api\ShelfController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\StatusProductController;
use App\Http\Controllers\Api\StatusToolMaterialController;
use App\Http\Controllers\Api\ToolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SegmentController;
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
            Route::get('/user-inactive', 'getUserInactive')->middleware(['auth:api', 'check_admin:1,5']);
            Route::put('/update-status-user/{id}', 'setActiveUser')->middleware(['auth:api', 'check_admin:1']);
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,5']);
            Route::post('/datatable-inactive', 'datatableInactive')->middleware(['auth:api', 'check_admin:1,5']);
        });
    });
    Route::prefix('group')->group(function () {
        Route::controller(GroupController::class)->group(function () {
            Route::get('/group', 'getGroups');
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('segment')->group(function () {
        Route::controller(SegmentController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('category')->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::get('/indexForProduct', 'indexForProduct')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('status-product')->group(function () {
        Route::controller(StatusProductController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('product')->group(function () {
        Route::controller(ProductController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::post('/add-new-status/{id}', 'addStatusLogProduct')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::post('/edit-location/{id}', 'editLocation')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/update-status-product/{id}', 'updateStatusProduct')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::get('/report-product', 'export');
            Route::post('/datatable-product-status', 'datatableProductPerStatus')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
        });
    });
    Route::prefix('shipping')->group(function () {
        Route::controller(ShippingController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('module')->group(function () {
        Route::controller(ModuleController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('shelf')->group(function () {
        Route::controller(ShelfController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('status-tool-material')->group(function () {
        Route::controller(StatusToolMaterialController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2']);
            // Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,5']);
        });
    });
    Route::prefix('tool')->group(function () {
        Route::controller(ToolController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::post('/update-status/{id}', 'setStatusLogTool')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::put('/edit-location/{id}', 'editLocation')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/multiple-image-status/{id}', 'addMultipleImagesStatus')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::get('/report-tool', 'export');
        });
    });
    Route::prefix('material')->group(function () {
        Route::controller(MaterialController::class)->group(function () {
            Route::get('/index', 'index')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::post('/create', 'store')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::get('/detail/{id}', 'show')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::put('/update/{id}', 'update')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::delete('/delete/{id}', 'destroy')->middleware(['auth:api', 'check_admin:1,2,3']);
            Route::post('/update-status/{id}', 'setStatusLogMaterial')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/datatable', 'datatable')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
            Route::put('/edit-location/{id}', 'editLocation')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::post('/multiple-image-status/{id}', 'addMultipleImagesStatus')->middleware(['auth:api', 'check_admin:1,2,3,4']);
            Route::get('/report-material', 'export');
        });
    });
    Route::prefix('seeder-product')->group(function () {
        Route::controller(SeederProductController::class)->group(function () {
            Route::get('/create-module', 'storeModule');
            Route::get('/create-segment', 'storeSegment');
            Route::get('/create-product/segment/{id}', 'storeProduct');
        });
    });
    Route::prefix('dashboard')->group(function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/index-status', 'countStatus')->middleware(['auth:api', 'check_admin:1,2,3,4,5']);
        });
    });
});
