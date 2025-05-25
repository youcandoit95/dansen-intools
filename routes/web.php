<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.home');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');

Route::get('/login', fn () => view('login'))->name('login');

use App\Http\Controllers\CabangController;

Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');

use App\Http\Controllers\SalesAgentController;

Route::resource('sales-agent', SalesAgentController::class);

use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
Route::delete('product_image/destroy/{id}', [ProductController::class, 'destroyImage'])->name('product_image.destroy');
Route::post('products/toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');

use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('products/export', function () {
    return Excel::download(new ProductExport, 'produk.xlsx');
})->name('products.export');

use App\Http\Controllers\SupplierController;

Route::resource('suppliers', SupplierController::class);

use App\Http\Controllers\CustomerController;

Route::resource('customers', CustomerController::class);

use App\Http\Controllers\ProductPriceController;

Route::resource('product-prices', ProductPriceController::class);

use App\Http\Controllers\DefaultSellPriceController;

Route::resource('default-sell-price', DefaultSellPriceController::class);

use App\Http\Controllers\CustomerPriceController;

Route::prefix('customer-prices')->name('customer-prices.')->group(function () {
    Route::get('/', [CustomerPriceController::class, 'index'])->name('index');
    Route::get('/create', [CustomerPriceController::class, 'create'])->name('create');
    Route::post('/', [CustomerPriceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CustomerPriceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerPriceController::class, 'update'])->name('update');
});

Route::post('/customers/{id}/blacklist', [CustomerPriceController::class, 'blacklist'])->name('customers.blacklist');
