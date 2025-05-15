<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.home');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn () => view('login'))->name('login');

use App\Http\Controllers\CabangController;

Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');


