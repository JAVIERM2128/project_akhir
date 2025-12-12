<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductDetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes for products and categories
Route::get('/products', [ProductCatalogController::class, 'index'])->name('products.index');
Route::get('/products/category/{categoryId}', [ProductCatalogController::class, 'index'])->name('products.category');
Route::get('/products/{product}', [\App\Http\Controllers\ProductDetailController::class, 'show'])->name('products.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->middleware(['auth'])->name('checkout');
Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'process'])->middleware(['auth'])->name('checkout.process');

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Product routes
    Route::resource('admin/products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');

    // Category routes
    Route::resource('admin/categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');

    // Transaction routes
    Route::resource('admin/transactions', \App\Http\Controllers\Admin\TransactionController::class)->except(['create', 'store', 'edit', 'destroy'])->names('admin.transactions');
    Route::put('/admin/transactions/{transaction}/status', [\App\Http\Controllers\Admin\TransactionController::class, 'updateStatus'])->name('admin.transactions.updateStatus');
    Route::post('/admin/transactions/{transaction}/receipt', [\App\Http\Controllers\Admin\TransactionController::class, 'uploadReceipt'])->name('admin.transactions.uploadReceipt');

    // Topup routes
    Route::get('/admin/topups', [\App\Http\Controllers\Admin\TopupController::class, 'index'])->name('admin.topups.index');
    Route::get('/admin/topups/all', [\App\Http\Controllers\Admin\TopupController::class, 'all'])->name('admin.topups.all');
    Route::put('/admin/topups/{topup}/approve', [\App\Http\Controllers\Admin\TopupController::class, 'approve'])->name('admin.topups.approve');
    Route::put('/admin/topups/{topup}/reject', [\App\Http\Controllers\Admin\TopupController::class, 'reject'])->name('admin.topups.reject');

    // User routes
    Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'show'])->names('admin.users');
    Route::put('/admin/users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('admin.users.changeRole');
    Route::put('/admin/users/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('admin.users.restore');

    // Sales Report routes
    Route::get('/admin/reports/sales', [\App\Http\Controllers\Admin\SalesReportController::class, 'index'])->name('admin.reports.sales');
    Route::get('/admin/reports/sales/export', [\App\Http\Controllers\Admin\SalesReportController::class, 'export'])->name('admin.reports.sales.export');
    Route::get('/admin/reports/sales/pdf', [\App\Http\Controllers\Admin\SalesReportController::class, 'pdf'])->name('admin.reports.sales.pdf');

    // Store Setting routes
    Route::get('/admin/settings', [\App\Http\Controllers\Admin\StoreSettingController::class, 'edit'])->name('admin.store-setting.edit');
    Route::put('/admin/settings', [\App\Http\Controllers\Admin\StoreSettingController::class, 'update'])->name('admin.store-setting.update');
});

Route::middleware('auth')->group(function () {
    // User top-up routes
    Route::get('/topup/create', [\App\Http\Controllers\TopupController::class, 'create'])->name('topup.create');
    Route::post('/topup', [\App\Http\Controllers\TopupController::class, 'store'])->name('topup.store');
    Route::get('/topup/history', [\App\Http\Controllers\TopupController::class, 'history'])->name('topup.history');

    // Cart routes
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartId}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartId}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

    // Transaction routes (Riwayat Transaksi)
    Route::get('/transactions', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
