<?php

use App\Http\Controllers\TalentController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkProgress;

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


Route::get('dashboard', function () {
    return view('pages.dashboard.index');
});

Route::controller(TalentController::class)->group(function () {
    Route::get('talent/', 'index');
    Route::get('talent/insert', 'insert');
    Route::get('talent/edit/{id}', 'edit');
    Route::get('talent/update-image/{id}', 'updateImage');

    Route::get('progress-work', 'progress');
});