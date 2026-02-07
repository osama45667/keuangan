<!doctype html>
<html lang="id" style="height: 100%; width: 100%;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Sistem Laporan Keuangan')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
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
        // Use dedicated endpoint so background works even if /storage is not exposed
        $bgUrl = route('api.user.background-image');
        // Append timestamp from cookie or session to bust cache when user updates background
        // Cookie persists across page navigations so background updates on all pages
        $bgTs = request()->cookie('bg_ts') ?? session('bg_ts');
        if ($bgTs) {
            $bgUrl .= '?ts=' . $bgTs;
        }
        $hasBg = true;
    }
    
    // Pass background info via CSS variables to avoid inline background conflicts
    $bgVars = $hasBg
        ? "--theme-bg-url: url('{$bgUrl}'); --theme-bg-size: {$bgSize};"
        : '';
@endphp
<body class="app-body @if($hasBg)has-bg theme-overlay-{{ $overlay }}@endif" @if($hasBg) style="{{ $bgVars }}" @endif>

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
