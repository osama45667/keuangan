<!doctype html>
<html lang="id">
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
    $bgUrl = $user && $user->theme_bg_path ? asset('storage/'.$user->theme_bg_path) : null;
    $overlay = $user?->theme_overlay ?? 'auto';
    $overlayClass = $overlay === 'light' ? 'theme-overlay-light' : 'theme-overlay-dark';
    $bgSize = $user?->theme_bg_size ?? 'cover';
@endphp
<body class="app-body {{ $bgUrl ? 'has-bg '.$overlayClass : '' }}" @if($bgUrl) style="--app-bg-url: url('{{ $bgUrl }}'); --app-bg-size: {{ $bgSize }};" @endif>
<div class="d-flex app-shell" style="min-height:100vh;">
    @include('partials.sidebar')
    <div class="flex-grow-1">
        @include('partials.navbar')
        <main class="container-fluid p-4">
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
