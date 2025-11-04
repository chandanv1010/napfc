<?php

use App\Http\Controllers\Ajax\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CustomerController;
use App\Http\Controllers\Frontend\ProductController as FrontendController;

Route::get('dang-nhap.html', [AuthController::class, 'index'])->name('customer.auth');
Route::post('dang-nhap.html', [AuthController::class, 'login'])->name('customer.login');
Route::get('dang-ky-tai-khoan.html', [AuthController::class, 'register'])->name('customer.register');
Route::post('dang-ky-tai-khoan.html', [AuthController::class, 'registerAccount'])->name('customer.register.store');
Route::post('dang-xuat.html', [AuthController::class, 'logout'])->name('customer.logout');
Route::get('quen-mat-khau.html', [AuthController::class, 'forgotPassword'])->name('customer.password.forgot');
Route::post('xac-thuc-email.html', [AuthController::class, 'verifyCustomerEmail'])->name('customer.password.verify');
Route::get('cap-nhat-mat-khau/{token}', [AuthController::class, 'updatePassword'])->name('customer.update.password');
Route::put('thay-doi-mat-khau', [AuthController::class, 'changePassword'])->name('customer.password.reset');


Route::post('ajax/transaction/create', [ProductController::class, 'createTransaction']);
Route::get('ajax/transaction/status', [ProductController::class, 'checkTransactionStatus']);
Route::post('ajax/account/buy', [ProductController::class, 'createAccountTransaction']);
Route::get('account/info/success/{code}', [FrontendController::class, 'success'])
    ->name('account.success');
Route::get('ajax/account/status/{code}', [ProductController::class, 'accountTransactionStatus'])
->name('account.status');



Route::middleware(['customer'])->group(function () {
    Route::get('profile', [CustomerController::class, 'profile'])->name('customer.profile');
    
});

