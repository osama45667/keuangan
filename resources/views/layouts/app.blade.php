<!doctype html>
<html lang="id" style="height: 100%; width: 100%;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Sistem Laporan Keuangan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
@php
    $user = auth()->user();
    $bgUrl = null;
    $hasBg = false;
    
    $themePath = $user?->theme_bg_path ?? request()->cookie('theme_bg_path');
    $bgSize = $user?->theme_bg_size ?? request()->cookie('theme_bg_size') ?? 'cover';
    $overlay = $user?->theme_overlay ?? request()->cookie('theme_overlay') ?? 'auto';

    if ($themePath) {
        // Use public disk URL so uploaded backgrounds resolve correctly
        $bgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($themePath);
        // Append timestamp from cookie or session to bust cache when user updates background
        // Cookie persists across page navigations so background updates on all pages
        $bgTs = request()->cookie('bg_ts') ?? session('bg_ts');
        if ($bgTs) {
            $bgUrl .= '?ts=' . $bgTs;
        }
        $hasBg = true;
    }
    
    // Overlay handled in CSS via .app-body.has-bg::before
    $bgStyle = $hasBg
        ? "background-image: url('{$bgUrl}'); background-size: {$bgSize}; background-repeat: no-repeat; background-position: center; background-attachment: fixed; background-color: #0f172a;"
        : '';
@endphp
<body class="app-body @if($hasBg)has-bg theme-overlay-{{ $overlay }}@endif" @if($hasBg) style="{{ $bgStyle }}" @endif>
    @if($hasBg)
        <div
            id="theme-bg"
            aria-hidden="true"
            style="background-image: url('{{ e($bgUrl) }}'); background-size: {{ $bgSize }};"
        ></div>
    @endif

<div class="d-flex app-shell" style="min-height:100vh;">
    @include('partials.sidebar')
    <div class="flex-grow-1">
        @include('partials.navbar')
        <main class="app-main container-fluid p-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
