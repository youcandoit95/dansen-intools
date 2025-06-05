<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\SalesAgentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\DefaultSellPriceController;
use App\Http\Controllers\CustomerPriceController;
use App\Http\Controllers\UserController;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.user.status', 'check.cabang.status'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', fn () => redirect()->route('dashboard'));

    // Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');

    // Sales Agent
    Route::resource('sales-agent', SalesAgentController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::delete('product_image/destroy/{id}', [ProductController::class, 'destroyImage'])->name('product_image.destroy');
    Route::post('products/toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::get('products/export', fn () => Excel::download(new ProductExport, 'produk.xlsx'))->name('products.export');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/blacklist', [CustomerController::class, 'blacklist'])->name('customers.blacklist');
    Route::post('/customers/{customer}/whitelist', [CustomerController::class, 'whitelist'])->name('customers.whitelist');

    // Product Prices
    Route::resource('product-prices', ProductPriceController::class);

    // Default Sell Price
    Route::resource('default-sell-price', DefaultSellPriceController::class);

    // Customer Prices
    Route::prefix('customer-prices')->name('customer-prices.')->group(function () {
        Route::get('/', [CustomerPriceController::class, 'index'])->name('index');
        Route::get('/create', [CustomerPriceController::class, 'create'])->name('create');
        Route::post('/', [CustomerPriceController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CustomerPriceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerPriceController::class, 'update'])->name('update');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/{user}/toggle/{field}', [UserController::class, 'toggle'])->name('toggle');
        Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
    });

});
