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
    // Background image endpoint
    Route::get('/api/user/background-image', function() {
        $user = auth()->user();
        if (!$user || !$user->theme_bg_path) {
            return response()->noContent();
        }
        
        try {
            $path = $user->theme_bg_path;
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return response()->noContent();
            }
            
            $file = \Illuminate\Support\Facades\Storage::disk('public')->get($path);
            $mime = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($path);
            
            return response($file)
                ->header('Content-Type', $mime)
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('ETag', hash('md5', $file));
        } catch (\Exception $e) {
            return response()->noContent();
        }
    })->name('api.user.background-image');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Debug theme route
    Route::get('/debug/theme', function() {
        $user = auth()->user();
        $bgUrl = ($user && $user->theme_bg_path)
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path)
            : null;
        
        $overlayClass = ($user?->theme_overlay ?? 'auto') === 'light' ? 'theme-overlay-light' : 'theme-overlay-dark';
        $bgSize = $user?->theme_bg_size ?? 'cover';
        
        $overlayCss = match ($user?->theme_overlay ?? 'auto') {
            'light' => 'linear-gradient(180deg, rgba(255,255,255,0.70), rgba(248,250,252,0.85))',
            'dark' => 'linear-gradient(180deg, rgba(2,6,23,0.55), rgba(2,6,23,0.70))',
            default => 'linear-gradient(180deg, rgba(2,6,23,0.35), rgba(2,6,23,0.55))',
        };
        
        return view('layouts.debug-theme', compact('user', 'bgUrl', 'bgSize', 'overlayClass', 'overlayCss'));
    })->name('debug.theme');

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
