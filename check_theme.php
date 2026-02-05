<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n=== FIREBASE THEME VERIFICATION ===\n\n";

$users = \App\Models\User::select('id', 'name', 'email', 'theme_bg_path', 'theme_overlay', 'theme_bg_size')->get();

foreach($users as $u) {
    echo "┌─ User: {$u->name} ({$u->email})\n";
    echo "├─ Path: " . ($u->theme_bg_path ?? 'NULL') . "\n";
    echo "├─ Overlay: " . ($u->theme_overlay ?? 'auto') . "\n";
    echo "├─ Size: " . ($u->theme_bg_size ?? 'cover') . "\n";
    
    if($u->theme_bg_path) {
        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($u->theme_bg_path);
        $filePath = storage_path('app/public/' . $u->theme_bg_path);
        $fileExists = file_exists($filePath);
        
        echo "├─ URL: $url\n";
        echo "├─ File Exists: " . ($fileExists ? 'YES ✓' : 'NO ✗') . "\n";
        
        if($fileExists) {
            $fileSize = filesize($filePath);
            echo "├─ File Size: " . round($fileSize / 1024, 2) . " KB\n";
        }
        
        // Test inline style that should appear in body tag
        $bgSize = $u->theme_bg_size ?? 'cover';
        $inlineStyle = "background-image: url('$url'); background-size: $bgSize; background-position: center; background-attachment: fixed; background-repeat: no-repeat; background-color: #0f172a;";
        echo "├─ Inline Style:\n";
        echo "|  " . str_repeat("─", 100) . "\n";
        echo "|  " . $inlineStyle . "\n";
        echo "|  " . str_repeat("─", 100) . "\n";
    }
    
    // Test body class
    $bodyClass = $u->theme_bg_path 
        ? "app-body has-bg theme-overlay-" . ($u->theme_overlay ?? 'auto')
        : "app-body";
    echo "└─ Body Class: $bodyClass\n";
    echo "\n";
}

echo "=== EXPECTED HTML ===\n";
if($users->first() && $users->first()->theme_bg_path) {
    $u = $users->first();
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($u->theme_bg_path);
    $bgSize = $u->theme_bg_size ?? 'cover';
    $overlay = $u->theme_overlay ?? 'auto';
    
    $html = <<<HTML
<body class="app-body has-bg theme-overlay-$overlay" style="background-image: url('$url'); background-size: $bgSize; background-position: center; background-attachment: fixed; background-repeat: no-repeat; background-color: #0f172a;">
    <!-- Content here -->
</body>
HTML;
    echo $html . "\n";
}
?>

