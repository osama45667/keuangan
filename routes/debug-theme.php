<?php

use Illuminate\Support\Facades\Route;

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
})->middleware('auth')->name('debug.theme');
