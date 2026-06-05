<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WasteLogController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlertController;

// Redirect root → dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// All routes require login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products — admin can create/edit/delete, staff can view
    Route::resource('products', ProductController::class);

    // Stock Check-In
    Route::get('/check-in',  [TransactionController::class, 'checkInForm'])->name('checkin.form');
    Route::post('/check-in', [TransactionController::class, 'checkIn'])->name('checkin.store');

    // Stock Check-Out
    Route::get('/check-out',  [TransactionController::class, 'checkOutForm'])->name('checkout.form');
    Route::post('/check-out', [TransactionController::class, 'checkOut'])->name('checkout.store');

    // Transaction history
    Route::get('/transactions', [TransactionController::class, 'history'])->name('transactions.history');


    // Alerts
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');

    // Waste & loss tracking
    Route::get('/waste',  [WasteLogController::class, 'index'])->name('waste.index');
    Route::post('/waste', [WasteLogController::class, 'store'])->name('waste.store');

    // Reports — admin only
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('admin')
        ->name('reports.index');
});

require __DIR__ . '/auth.php';