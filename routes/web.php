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
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\CetakLabelController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\SellPriceSettingController;
use App\Http\Controllers\Api\InvoiceSupportController;
use Illuminate\Support\Facades\Auth; // pastikan ini di atas

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

Route::get('/check-session', function () {
    return response()->json(['authenticated' => Auth::check()]);
});


/*
|--------------------------------------------------------------------------
| Protected Routes (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.user.status', 'check.cabang.status'])->group(function () {

    Route::middleware(['checkrole:superadmin,manager,supervisor'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Purchase Order
        Route::resource('purchase-order', PurchaseOrderController::class);
        Route::get('purchase-order/{purchase_order}/item/create', [PurchaseOrderItemController::class, 'create'])->name('purchase-order-item.create');
        Route::post('purchase-order/{purchase_order}/item', [PurchaseOrderItemController::class, 'store'])->name('purchase-order-item.store');
        Route::delete('/purchase-order-item/{id}', [PurchaseOrderItemController::class, 'destroy'])->name('purchase-order-item.destroy');
        Route::get('/purchase-order/{purchaseOrder}/ajukan', [PurchaseOrderController::class, 'ajukan'])->name('purchase-order.ajukan');
        Route::post('/purchase-order/{purchaseOrder}/kirim-email', [PurchaseOrderController::class, 'kirimEmail'])->name('purchase-order.kirim-email');
        // Ajukan
        Route::get('/purchase-order/{purchaseOrder}/ajukan', [PurchaseOrderController::class, 'ajukan'])
            ->name('purchase-order.ajukan');

        // Kirim Email
        Route::get('/purchase-order/{purchaseOrder}/kirim-email', [PurchaseOrderController::class, 'kirimEmail'])
            ->name('purchase-order.kirim-email');

        // Batalkan
        Route::get('/purchase-order/{purchaseOrder}/batalkan', [PurchaseOrderController::class, 'batalkan'])
            ->name('purchase-order.batalkan');

        /*
        |--------------------------------------------------------------------------
        | company
        |--------------------------------------------------------------------------
        */
        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::get('/create', [CompanyController::class, 'create'])->name('create');
            Route::post('/store', [CompanyController::class, 'store'])->name('store');
            Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
            Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
            Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
            Route::post('/restore/{id}', [CompanyController::class, 'restore'])->name('restore');
            Route::post('/blacklist/{id}', [CompanyController::class, 'blacklist'])->name('blacklist');
            Route::post('/unblacklist/{id}', [CompanyController::class, 'unblacklist'])->name('unblacklist');
        });

        /*
        |--------------------------------------------------------------------------
        | inbound
        |--------------------------------------------------------------------------
        */

        Route::prefix('inbound')->name('inbound.')->group(function () {
            Route::get('/', [InboundController::class, 'index'])->name('index');
            Route::get('/create', [InboundController::class, 'create'])->name('create');
            Route::post('/store', [InboundController::class, 'store'])->name('store');
            Route::get('/{inbound}/edit', [InboundController::class, 'edit'])->name('edit');
            Route::put('/{inbound}/update', [InboundController::class, 'update'])->name('update');
            Route::get('/api/po-detail/{id}', [App\Http\Controllers\PurchaseOrderController::class, 'getPoDetail'])->name('api.po-detail');
            Route::get('/{inbound}/submit', [InboundController::class, 'submitInbound'])->name('submit');

            Route::get('/{inbound}/surat-jalan/file/{filename}', function ($inbound, $filename) {
                $path = storage_path("app/private/inbound/{$inbound}/surat-jalan/{$filename}");

                if (!\Illuminate\Support\Facades\File::exists($path)) {
                    abort(404, 'File tidak ditemukan');
                }

                return response()->file($path);
            })->name('surat-jalan.file');

            Route::get('/{inbound}/hapus-foto/{field}', [InboundController::class, 'hapusFoto'])->name('hapus-foto');
            Route::get('/{id}/cancel', [InboundController::class, 'cancel'])->name('cancel');


        });

        /*
        |--------------------------------------------------------------------------
        | stok
        |--------------------------------------------------------------------------
        */
        Route::post('/stok/store', [StokController::class, 'store'])->name('stok.store');
        Route::delete('/stok/{id}', [StokController::class, 'delete'])->name('stok.delete');
        Route::get('/stok', [StokController::class, 'index'])->name('stok.index');
        Route::get('/stok/{id}', [StokController::class, 'show'])->name('stok.show');
        Route::post('/stok/{stok}/transfer', [StokController::class, 'transfer'])->name('stok.transfer');

        /*
        |--------------------------------------------------------------------------
        | invoice
        |--------------------------------------------------------------------------
        */
        Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::post('invoice', [InvoiceController::class, 'store'])->name('invoice.store');

        Route::get('invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::get('invoice/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
        Route::put('invoice/{invoice}', [InvoiceController::class, 'update'])->name('invoice.update');

        // Tambah produk satuan setelah invoice dibuat
        Route::get('invoice/{invoice}/produk', [InvoiceController::class, 'produk'])->name('invoice.produk');
        Route::post('invoice/{invoice}/produk', [InvoiceController::class, 'tambahProduk'])->name('invoice.produk.store');

        /*
        |--------------------------------------------------------------------------
        | invoice item
        |--------------------------------------------------------------------------
        */
        Route::post('/invoice-item/store', [InvoiceItemController::class, 'store'])->name('invoice-item.store');
        Route::post('/invoice/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoice.cancel');

        /*
        |--------------------------------------------------------------------------
        | API
        |--------------------------------------------------------------------------
        */
        // API Stok berdasarkan produk
        Route::get('/api/stok-by-product/{product_id}', [InvoiceSupportController::class, 'stokByProduct']);
        // API Customer Price berdasarkan product dan customer
        Route::get('/api/customer-price-by-product/{customer_id}/{product_id}', [InvoiceSupportController::class, 'customerPrice']);

        Route::get('/api/product-detail/{id}', [InvoiceSupportController::class, 'productDetail']);
        Route::get('/api/stock-detail/{id}', [InvoiceSupportController::class, 'stokDetail']);


        /*
        |--------------------------------------------------------------------------
        | cetak
        |--------------------------------------------------------------------------
        */
        Route::get('/cetak-label', [CetakLabelController::class, 'show'])->name('cetak.label');
        Route::post('/cetak-label/update', [CetakLabelController::class, 'markAsPrinted'])->name('cetak.label.mark');
    });

    Route::middleware(['checkrole:superadmin'])->group(function () {

        // Cabang
        Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');

        // Sales Agent
        Route::resource('sales-agent', SalesAgentController::class);

        // Products
        Route::resource('products', ProductController::class);
        Route::delete('product_image/destroy/{id}', [ProductController::class, 'destroyImage'])->name('product_image.destroy');
        Route::post('products/toggle-status/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::get('products/export', fn() => Excel::download(new ProductExport, 'produk.xlsx'))->name('products.export');

        // Suppliers
        Route::resource('suppliers', SupplierController::class);

        Route::prefix('sell-price-settings')->middleware(['auth'])->group(function () {
            Route::get('/', [SellPriceSettingController::class, 'index'])->name('sell-price-settings.index');
            Route::get('/create', [SellPriceSettingController::class, 'create'])->name('sell-price-settings.create');
            Route::post('/', [SellPriceSettingController::class, 'store'])->name('sell-price-settings.store');
        });

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
});
