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
    
    if ($user && $user->theme_bg_path) {
        $bgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
        // Escape single quotes and backslashes for CSS URL
        $bgUrl = str_replace("'", "\\'", $bgUrl);
        $hasBg = true;
    }
    
    $bgSize = $user?->theme_bg_size ?? 'cover';
    $overlay = $user?->theme_overlay ?? 'auto';
@endphp
<body class="app-body @if($hasBg)has-bg theme-overlay-{{ $overlay }}@endif">
    @if($hasBg)
        <div id="theme-bg" data-theme-size="{{ $bgSize }}" data-theme-overlay="{{ $overlay }}" style="background-image: url('{{ $bgUrl }}');"></div>
        <script>
            (function(){
                var el = document.getElementById('theme-bg');
                if (!el) return;
                var size = el.getAttribute('data-theme-size') || 'cover';
                el.style.backgroundSize = size;
                var overlay = el.getAttribute('data-theme-overlay');
                if (overlay) {
                    document.body.classList.add('theme-overlay-' + overlay);
                }
            })();
        </script>
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
