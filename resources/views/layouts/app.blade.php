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
    $bgUrl = ($user && $user->theme_bg_path)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path)
        : null;
    $bgSize = $user?->theme_bg_size ?? 'cover';
    $overlay = $user?->theme_overlay ?? 'auto';
@endphp
<body class="app-body @if($bgUrl)has-bg theme-overlay-{{ $overlay }}@endif"@if($bgUrl) style="background-image: url('{{ $bgUrl }}'); background-size: {{ $bgSize }}; background-position: center; background-attachment: fixed; background-repeat: no-repeat;"@endif>
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

<!-- Theme Background Script - Apply at runtime -->
@php
    $user = auth()->user();
    $bgUrl = ($user && $user->theme_bg_path)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path)
        : null;
    $bgSize = $user?->theme_bg_size ?? 'cover';
@endphp

@if($bgUrl)
<script>
    // Robust background insertion: create fixed background + overlay DIVs
    (function() {
        function applyThemeBackground() {
            try {
                // prevent duplicate
                if (document.getElementById('app-theme-bg')) return;

                const bgUrl = '{{ $bgUrl }}';
                const bgSize = '{{ $bgSize }}';

                // background container
                const bgDiv = document.createElement('div');
                bgDiv.id = 'app-theme-bg';
                bgDiv.style.position = 'fixed';
                bgDiv.style.top = '0';
                bgDiv.style.left = '0';
                bgDiv.style.width = '100%';
                bgDiv.style.height = '100%';
                bgDiv.style.zIndex = '0';
                bgDiv.style.pointerEvents = 'none';
                bgDiv.style.backgroundImage = `url("${bgUrl}")`;
                bgDiv.style.backgroundSize = bgSize;
                bgDiv.style.backgroundPosition = 'center';
                bgDiv.style.backgroundRepeat = 'no-repeat';
                bgDiv.style.backgroundAttachment = 'fixed';
                bgDiv.style.opacity = '1';

                // overlay above bgDiv
                const overlay = document.createElement('div');
                overlay.id = 'app-theme-overlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.zIndex = '1';
                overlay.style.pointerEvents = 'none';
                overlay.style.background = 'linear-gradient(180deg, rgba(2,6,23,0.35), rgba(2,6,23,0.55))';
                overlay.style.transition = 'opacity 0.35s ease-in-out';
                overlay.style.opacity = '1';

                // ensure content stacks above overlay
                const shell = document.querySelector('.app-shell');
                if (shell) shell.style.zIndex = '10';
                const navbar = document.querySelector('.app-navbar');
                if (navbar) navbar.style.zIndex = '20';
                const sidebar = document.querySelector('.app-sidebar');
                if (sidebar) sidebar.style.zIndex = '15';

                // insert at document start so it's behind everything
                document.documentElement.insertBefore(bgDiv, document.body);
                document.documentElement.insertBefore(overlay, document.body);

                console.log('✓ App theme background DIV inserted', { url: bgUrl, size: bgSize });
            } catch (err) {
                console.error('Error applying theme background', err);
            }
        }

        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            applyThemeBackground();
        } else {
            document.addEventListener('DOMContentLoaded', applyThemeBackground);
            window.addEventListener('load', applyThemeBackground);
        }
    })();
</script>
@endif

</body>
</html>
