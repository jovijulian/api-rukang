<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\StatusProductController;
use App\Http\Controllers\StatusToolMaterialController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('signin');
});
Route::get('/signup', function () {
    return view('signup');
});

Route::get('/verification', [RegisterController::class, 'verif']);
Route::get('/verification-success', [RegisterController::class, 'verification_success'])->name('verification-success');

Route::get('/forgot-password', [ForgetPasswordController::class, 'sendEmail']);
Route::get('/forgot-password/verif-email', [ForgetPasswordController::class, 'verifEmail']);
Route::get('/change-password', [ForgetPasswordController::class, 'newPassword'])->name('change-password');
Route::get('/change-password-success', [ForgetPasswordController::class, 'changePasswordSuccess'])->name('change-password-success');



Route::get('dashboard', function () {
    return view('pages.dashboard.index');
});

Route::controller(UserController::class)->group(function () {
    Route::get('user/', 'index');
    Route::get('user/insert', 'insert');
    Route::get('user/inactive-user', 'inactiveUser');
});

Route::controller(SegmentController::class)->group(function () {
    Route::get('segment/', 'index');
    Route::get('segment/insert', 'insert');
    Route::get('segment/edit/{id}', 'edit');
});

Route::controller(ShelfController::class)->group(function () {
    Route::get('shelf/', 'index');
    Route::get('shelf/insert', 'insert');
    Route::get('shelf/edit/{id}', 'edit');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('category/', 'index');
    Route::get('category/insert', 'insert');
    Route::get('category/edit/{id}', 'edit');
});

Route::controller(StatusProductController::class)->group(function () {
    Route::get('status-product/', 'index');
    Route::get('status-product/insert', 'insert');
    Route::get('status-product/edit/{id}', 'edit');
});

Route::controller(StatusToolMaterialController::class)->group(function () {
    Route::get('status-tool-material/', 'index');
    Route::get('status-tool-material/insert', 'insert');
    Route::get('status-tool-material/edit/{id}', 'edit');
});

Route::controller(ModuleController::class)->group(function () {
    Route::get('module/', 'index');
    Route::get('module/insert', 'insert');
    Route::get('module/edit/{id}', 'edit');
});

Route::controller(ShippingController::class)->group(function () {
    Route::get('shipping/', 'index');
    Route::get('shipping/insert', 'insert');
    Route::get('shipping/edit/{id}', 'edit');
});

Route::controller(GroupController::class)->group(function () {
    Route::get('group/', 'index');
    Route::get('group/insert', 'insert');
    Route::get('group/edit/{id}', 'edit');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('product/', 'index');
    Route::get('product/detail/{id}', 'detail');
    Route::get('product/insert', 'insert');
    Route::get('product/add-status/{id}', 'addStatus');
    Route::get('product/edit/{id}', 'edit');
    Route::get('product/edit-status/{idProduct}/{idStatus}', 'editStatus');
    Route::get('product/edit-location/{id}', 'editLocation');
    
    Route::get('product-by-status/{statusId}', 'productByStatus');
});

Route::controller(ToolController::class)->group(function () {
    Route::get('tool/', 'index');
    Route::get('tool/detail/{id}', 'detail');
    Route::get('tool/insert', 'insert');
    Route::get('tool/update-status/{id}', 'updateStatus');
    Route::get('tool/edit/{id}', 'edit');
    Route::get('tool/edit-status/{idProduct}/{idStatus}', 'editStatus');
    Route::get('tool/edit-location/{id}', 'editLocation');
});

Route::controller(MaterialController::class)->group(function () {
    Route::get('material/', 'index');
    Route::get('material/detail/{id}', 'detail');
    Route::get('material/insert', 'insert');
    Route::get('material/update-status/{id}', 'updateStatus');
    Route::get('material/edit-status/{idProduct}/{idStatus}', 'editStatus');
    Route::get('material/edit/{id}', 'edit');
    Route::get('material/edit-location/{id}', 'editLocation');
});
