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
        // Use our dedicated endpoint instead of direct storage URL
        $bgUrl = route('api.user.background-image');
        // Append timestamp from cookie or session to bust cache when user updates background
        // Cookie persists across page navigations so background updates on all pages
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
            if (!el) {
                console.error('[BG] Element not found');
                return;
            }
            
            var url = el.getAttribute('data-bg-url') || '';
            var size = el.getAttribute('data-bg-size') || 'cover';
            
            if (!url || !url.trim()) {
                console.error('[BG] No URL found in data attribute');
                return;
            }
            
            console.log('[BG] Applying background: ' + url + ' | Size: ' + size);
            
            // Use a new Image to preload and check if URL is valid
            var img = new Image();
            img.onload = function() {
                console.log('[BG] Image loaded successfully, applying to background');
                el.style.backgroundImage = 'url("' + url + '")';
                el.style.backgroundSize = size;
            };
            img.onerror = function() {
                console.error('[BG] Failed to load image from: ' + url);
                // Still apply it anyway - browser might have cached it
                el.style.backgroundImage = 'url("' + url + '")';
                el.style.backgroundSize = size;
            };
            img.src = url;
        }
        
        // Apply immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyBackgroundImage);
        } else {
            applyBackgroundImage();
        }
        
        // Also apply after a small delay
        setTimeout(applyBackgroundImage, 100);
        
        // Listen for size mode changes and update background size in real-time
        var sizeSelect = document.getElementById('theme_bg_size');
        if (sizeSelect) {
            sizeSelect.addEventListener('change', function() {
                var el = document.getElementById('theme-bg');
                if (el) {
                    el.setAttribute('data-bg-size', this.value);
                    el.style.backgroundSize = this.value;
                    console.log('[BG] Size mode changed to: ' + this.value);
                }
            });
        }
        
        // Listen for overlay mode changes and update body class in real-time
        var overlaySelect = document.getElementById('theme_overlay');
        if (overlaySelect) {
            overlaySelect.addEventListener('change', function() {
                var body = document.querySelector('.app-body');
                if (body) {
                    // Remove all overlay classes
                    body.classList.remove('theme-overlay-light', 'theme-overlay-dark', 'theme-overlay-auto');
                    // Add new class
                    body.classList.add('theme-overlay-' + this.value);
                    console.log('[BG] Overlay mode changed to: ' + this.value);
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
