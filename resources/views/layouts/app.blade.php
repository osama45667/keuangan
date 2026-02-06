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

<!-- Apply theme background with !important override -->
@if($bgUrl)
<script>
    (function() {
        function applyTheme() {
            const body = document.body;
            const bgUrl = '{{ addslashes($bgUrl) }}';
            const bgSize = '{{ $bgSize }}';
            
            // Force background-image with !important
            body.style.setProperty('background-image', `url("${bgUrl}")`, 'important');
            body.style.setProperty('background-size', bgSize, 'important');
            body.style.setProperty('background-position', 'center', 'important');
            body.style.setProperty('background-attachment', 'fixed', 'important');
            body.style.setProperty('background-repeat', 'no-repeat', 'important');
            
            // Ensure overlay visible
            const overlay = document.querySelector('.app-body::before');
            if (overlay) {
                overlay.style.opacity = '1';
            }
            
            console.log('✓ Theme applied:', bgUrl);
        }
        
        // Apply immediately and on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applyTheme);
        } else {
            applyTheme();
        }
    })();
</script>
@endif

</body>
</html>
