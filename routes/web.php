<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    // Route::resource('talentos', TalentsController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('usuarios', UserController::class);
    Route::resource('roles', RoleController::class);
});
