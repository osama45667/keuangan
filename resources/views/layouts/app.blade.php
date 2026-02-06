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
        // Ambil URL dari disk public supaya file upload bisa diakses
        $bgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
        // Tambah timestamp untuk cache-bust
        $bgTs = request()->cookie('bg_ts') ?? session('bg_ts');
        if ($bgTs) {
            $bgUrl .= '?ts=' . $bgTs;
        }
        $hasBg = true;
    }
    
    $bgSize = $user?->theme_bg_size ?? 'cover';
    $overlay = $user?->theme_overlay ?? 'auto';
@endphp
<body class="app-body @if($hasBg)has-bg theme-overlay-{{ $overlay }}@endif">
    @if($hasBg)
        <div id="theme-bg" data-bg-url="{!! $bgUrl !!}" data-bg-size="{{ $bgSize }}" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1; background-color:#f5f5f5; background-position:center; background-repeat:no-repeat; background-attachment:fixed; background-size:cover; pointer-events:none;"></div>
        <script>
        function applyBackgroundImage() {
            var el = document.getElementById('theme-bg');
            if (!el) return;
            
            var url = el.getAttribute('data-bg-url') || '';
            var size = el.getAttribute('data-bg-size') || 'cover';
            if (!url.trim()) return;
            
            var img = new Image();
            img.onload = function() {
                el.style.backgroundImage = 'url("' + url + '")';
                el.style.backgroundSize = size;
            };
            img.onerror = function() {
                el.style.backgroundImage = 'url("' + url + '")';
                el.style.backgroundSize = size;
            };
            img.src = url;
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyBackgroundImage);
        } else {
            applyBackgroundImage();
        }
        setTimeout(applyBackgroundImage, 100);
        
        var sizeSelect = document.getElementById('theme_bg_size');
        if (sizeSelect) {
            sizeSelect.addEventListener('change', function() {
                var el = document.getElementById('theme-bg');
                if (el) {
                    el.setAttribute('data-bg-size', this.value);
                    el.style.backgroundSize = this.value;
                }
            });
        }
        
        var overlaySelect = document.getElementById('theme_overlay');
        if (overlaySelect) {
            overlaySelect.addEventListener('change', function() {
                var body = document.querySelector('.app-body');
                if (body) {
                    body.classList.remove('theme-overlay-light', 'theme-overlay-dark', 'theme-overlay-auto');
                    body.classList.add('theme-overlay-' + this.value);
                }
            });
        }
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
