<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/activate', [AuthController::class, 'activate'])->name('student.activate');
    Route::post('/password/forgot', [AuthController::class, 'sendResetCode'])->name('password.sendReset');
    Route::post('/password/verify', [AuthController::class, 'verifyResetCode'])->name('password.verify');
    Route::post('/password/reset', [AuthController::class, 'resetNewPassword'])->name('password.reset');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
    Route::post('/user/register', [UserController::class, 'register'])->name('user.register');
    Route::get('/activate/{token}', [UserController::class, 'activate'])->name('user.activate');
});
