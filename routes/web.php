<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\AccountController;
use App\Http\Controllers\Master\PeriodController;
use App\Http\Controllers\Transaction\JournalController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Family\FamilyCategoryController;
use App\Http\Controllers\Family\FamilyTransactionController;
use App\Http\Controllers\Family\FamilyReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Redirect legacy accounting pages to family feature
    Route::get('journals', function () {
        return redirect()->route('family.transactions.index');
    })->name('journals.index');

    Route::middleware(['permission:manage family'])->group(function () {
        Route::prefix('family')->name('family.')->group(function () {
            Route::resource('categories', FamilyCategoryController::class)->except(['show']);
            Route::resource('transactions', FamilyTransactionController::class)->except(['show']);
        });
    });

    Route::middleware(['permission:view family'])->group(function () {
        Route::get('family/reports/summary', [FamilyReportController::class, 'summary'])->name('family.reports.summary');
    });

    Route::middleware(['permission:export family'])->group(function () {
        Route::get('family/reports/export/{format}', [FamilyReportController::class, 'export'])->name('family.reports.export');
    });

    // Accounting reports disabled for family mode
});

require __DIR__.'/auth.php';
