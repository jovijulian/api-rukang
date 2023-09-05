<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgetPasswordController;
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


Route::get('product', function () {
    return view('pages.product.index');
});


