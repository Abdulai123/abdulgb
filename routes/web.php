<?php

use App\Http\Controllers\GeneralController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Colors\Rgb\Channels\Red;

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

//Route::get('/monero/wallet/test/rpc', [WalletController::class, 'test']);

// Other routes...
// Route::get('/cap', function () {
//     return GeneralController::captchaGen();
// });

Route::get('/', [UserController::class, 'show']);

// Authentication 
Route::middleware(['guest'])->group(function () {
    Route::get('/auth/{action}', [UserController::class, 'create']);
    Route::post('/auth/login', [UserController::class, 'authLogin']);
    Route::post('/auth/signup', [UserController::class, 'store']);
    Route::get('/captcha', [GeneralController::class, 'captcha']);
    Route::get('/canary.txt', [GeneralController::class, 'canary']);
});

// Include user routes from user.php
require_once('user.php');
Route::get('/canary.txt', [GeneralController::class, 'canary']);


Route::middleware(['role:store'])->group(function () {
    
    // Include store routes from store.php
    require_once('store.php');
});

Route::middleware(['role:share'])->group(function () {
    // Include store routes from store.php
    require_once('share.php');
});


Route::middleware(['role:junior'])->group(function () {
    // Include store routes from store.php
    require_once('junior.php');
});

Route::middleware(['role:senior'])->group(function () {
    // Include store routes from store.php
    require_once('senior.php');
});

Route::middleware(['role:admin'])->group(function () {
    // Include admin routes from admin.php
    require_once('admin.php');
});



// });
Route::get('/logout', [UserController::class, 'userLogout']);
