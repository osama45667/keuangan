<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Debug - Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.debug-theme {
            background-image: 
                {{ $bgUrl ? "url('" . $bgUrl . "')" : "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" }};
            background-size: {{ $bgSize }};
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
        
        .debug-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 2rem;
            margin: 2rem;
        }
        
        .debug-item {
            padding: 1rem;
            border-left: 4px solid #2563eb;
            background: #f0f4f8;
            margin: 1rem 0;
            border-radius: 4px;
        }
        
        .preview-box {
            height: 300px;
            background: inherit;
            background-image: 
                {{ $bgUrl ? "url('" . $bgUrl . "')" : "linear-gradient(135deg, #667eea 0%, #764ba2 100%)" }};
            background-size: {{ $bgSize }};
            background-position: center;
            background-attachment: fixed;
            border-radius: 8px;
            margin: 2rem 0;
            border: 2px solid #e5e7eb;
        }
        
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body @class(['debug-theme'])>
    <div class="debug-container">
        <h1 class="mb-4">🎨 Theme Debug Panel</h1>
        
        <div class="debug-item">
            <strong>Status User:</strong> 
            <code>{{ auth()->check() ? auth()->user()->name : 'Not Authenticated' }}</code>
        </div>
        
        @if($user && $bgUrl)
            <div class="alert alert-success">✅ Theme is configured and should be visible!</div>
            
            <div class="debug-item">
                <strong>Background URL:</strong><br>
                <code style="word-break: break-all;">{{ $bgUrl }}</code>
            </div>
            
            <div class="debug-item">
                <strong>Background Size:</strong> <code>{{ $bgSize }}</code>
            </div>
            
            <div class="debug-item">
                <strong>Overlay Mode:</strong> <code>{{ $user->theme_overlay ?? 'auto' }}</code>
            </div>
            
            <div class="debug-item">
                <strong>Database Path:</strong><br>
                <code style="word-break: break-all;">{{ $user->theme_bg_path }}</code>
            </div>
            
            <h3 class="mt-4 mb-3">📋 Inline Style (Body)</h3>
            <div class="code-block">
style="--app-bg-url: url('{{ $bgUrl }}'); --app-bg-size: {{ $bgSize }}; --app-bg-overlay: {{ str_replace(["\n", "\t"], '', $overlayCss) }};"
            </div>
            
            <h3 class="mt-4 mb-3">🎯 Preview</h3>
            <div class="preview-box"></div>
            
            <div class="alert alert-info">
                <strong>Tips:</strong>
                <ul class="mb-0 mt-2">
                    <li>If the preview box shows your image, the CSS is working correctly</li>
                    <li>Check browser DevTools (F12) → Elements → Check body styles</li>
                    <li>Verify CSS variables are set: <kbd>--app-bg-url</kbd>, <kbd>--app-bg-size</kbd>, <kbd>--app-bg-overlay</kbd></li>
                    <li>Clear browser cache if changes don't appear</li>
                </ul>
            </div>
            
        @else
            <div class="alert alert-warning">⚠️ No theme configured yet!</div>
            <p>Go to <a href="{{ route('profile.edit') }}" class="btn btn-primary">Profile Settings</a> to upload a background image.</p>
        @endif
        
        <hr class="my-4">
        
        <div class="text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to App</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Theme</a>
        </div>
    </div>
</body>
</html>
