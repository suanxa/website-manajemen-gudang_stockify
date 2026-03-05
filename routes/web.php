<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect('/admin/dashboard');
    }

    if ($user->isManajerGudang()) {
        return redirect('/manager/dashboard');
    }

    if ($user->isStaffGudang()) {
        return redirect('/staff/dashboard');
    }

    abort(403);
})->middleware('auth')->name('dashboard');

/**
 * ROUTES KHUSUS ADMIN
 */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. PRODUK 
Route::prefix('products')->name('products.')->group(function () {
    
    // --- 2.1. KATEGORI (PINDAHKAN KE ATAS) ---
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store'); // Ubah '/' jadi '/store'
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // --- 2.2. ATRIBUT (PINDAHKAN KE ATAS) ---
    Route::prefix('attributes')->name('attributes.')->group(function () {
        Route::get('/', [AttributeController::class, 'index'])->name('index');
        Route::post('/store', [AttributeController::class, 'store'])->name('store'); // Ubah '/' jadi '/store'
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('destroy');
    });

    // --- Rute Khusus Produk ---
    Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
    Route::get('/import-export', [ProductController::class, 'importExportView'])->name('import-export');
    Route::post('/import', [ProductController::class, 'import'])->name('import');
    Route::get('/export', [ProductController::class, 'export'])->name('export');
    Route::get('/template', [ProductController::class, 'template'])->name('template');
    Route::get('/products-gallery', [ReportController::class, 'productGallery'])->name('gallery');

    // --- Rute CRUD Produk Utama (TARUH PALING BAWAH) ---
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::post('/{id}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('force-delete');
});
    // 3. PENGIRIM 
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('index');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    // 4. STOK 
    Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/history', [StockController::class, 'index'])->name('history');
    Route::post('/transactions', [StockController::class, 'store'])->name('store');
    Route::get('/opname', [StockController::class, 'opnameIndex'])->name('opname');
    Route::post('/opname', [StockController::class, 'storeOpname'])->name('opname.store');
    Route::get('/settings', [StockController::class, 'lowStockSettings'])->name('settings');
    Route::post('/confirm/{id}', [StockController::class, 'confirm'])->name('confirm');
    });

    // 5. PENGGUNA
    Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // 6. LAPORAN
    Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
    Route::get('/stock/export', [ReportController::class, 'exportStock'])->name('stock.export');
    Route::get('/transactions', [ReportController::class, 'transactionReport'])->name('transactions');
    Route::get('/activities', [ReportController::class, 'activityReport'])->name('activities');
    Route::get('/transactions/export', [ReportController::class, 'exportTransactions'])->name('transactions.export');
    });

    // 7. PENGATURAN
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');
});

/**
 * ROUTES KHUSUS MANAGER
 */
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    
    // 1. Dashboard Manager
    Route::get('/dashboard', [DashboardController::class, 'managerindex'])->name('dashboard');

    // 2. PRODUK ( Yang bisa diakses )
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::post('/{id}', [ProductController::class, 'update'])->name('update');
        Route::get('/products-gallery', [ReportController::class, 'productGallery'])->name('gallery');
    });

    // 3. SUPPLIER ( Lihat saja )
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

    // 4. STOK ( Riwayat & Opname/pengecekan )
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/history', [StockController::class, 'index'])->name('index'); 
        Route::post('/transactions', [StockController::class, 'store'])->name('store');
        Route::get('/opname', [StockController::class, 'opnameIndex'])->name('opname');
        Route::post('/opname', [StockController::class, 'storeOpname'])->name('opname.store');
    });

    // 5. LAPORAN
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        Route::get('/transactions', [ReportController::class, 'transactionReport'])->name('transactions');
        Route::get('/stock/export', [ReportController::class, 'exportStock'])->name('stock.export');
        Route::get('/transactions/export', [ReportController::class, 'exportTransactions'])->name('transactions.export');
    });
});

/**
 * ROUTES KHUSUS STAFF
 */
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    
    // 1. Dashboard Staff ( Daftar tugas/konfirmasi )
    Route::get('/dashboard', [DashboardController::class, 'staffIndex'])->name('dashboard');

    // 2. Stok ( Konfirmasi & Riwayat )
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/history', [StockController::class, 'index'])->name('index');
        Route::post('/transactions', [StockController::class, 'store'])->name('store');
        Route::post('/confirm-action/{id}', [StockController::class, 'confirm'])->name('confirm');
        // Fitur Pengecekan
        Route::get('/opname', [StockController::class, 'opnameIndex'])->name('opname');
        Route::post('/opname', [StockController::class, 'storeOpname'])->name('opname.store');
    });

    // 3. Produk ( Hanya Lihat Daftar untuk pengecekan )
    Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/gallery', [ReportController::class, 'productGallery'])->name('gallery');
    });
});

/**
 * PROFILE & AUTH
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';