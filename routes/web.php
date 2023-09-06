<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DescriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\SegmentController;
use App\Http\Controllers\StatusController;
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
    Route::get('user/inactive-user', 'inactiveUser');
});

Route::controller(SegmentController::class)->group(function () {
    Route::get('segment/', 'index');
    Route::get('segment/insert', 'insert');
    Route::get('segment/edit/{id}', 'edit');
});

Route::controller(DescriptionController::class)->group(function () {
    Route::get('description/', 'index');
    Route::get('description/insert', 'insert');
    Route::get('description/edit/{id}', 'edit');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('category/', 'index');
    Route::get('category/insert', 'insert');
    Route::get('category/edit/{id}', 'edit');
});

Route::controller(StatusController::class)->group(function () {
    Route::get('status/', 'index');
    Route::get('status/insert', 'insert');
    Route::get('status/edit/{id}', 'edit');
});

Route::get('product', function () {
    return view('pages.product.index');
});


