<?php
// Debug background status
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

// Get authenticated user
$user = auth()->user();

echo "<pre style='background:#1a1a1a; color:#0f0; padding:20px; font-family:monospace; border-radius:8px;'>";
echo "=== BACKGROUND DEBUG STATUS ===\n\n";

if (!$user) {
    echo "❌ NOT AUTHENTICATED\n";
    echo "Please login first.\n";
} else {
    echo "✓ User: {$user->name}\n";
    echo "✓ Email: {$user->email}\n\n";
    
    echo "--- Database Info ---\n";
    echo "theme_bg_path: " . ($user->theme_bg_path ? "✓ {$user->theme_bg_path}" : "❌ NULL") . "\n";
    echo "theme_bg_size: " . ($user->theme_bg_size ?: "auto (default)") . "\n";
    echo "theme_overlay: " . ($user->theme_overlay ?: "auto (default)") . "\n\n";
    
    if ($user->theme_bg_path) {
        echo "--- Storage File Check ---\n";
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $storagePath = "themes/" . basename($user->theme_bg_path);
        $fileExists = $disk->exists($storagePath);
        echo "Storage path: storage/app/public/{$storagePath}\n";
        echo "File exists: " . ($fileExists ? "✓ YES" : "❌ NO") . "\n";
        
        if ($fileExists) {
            $size = $disk->size($storagePath);
            echo "File size: " . ($size / 1024) . " KB\n\n";
        }
        
        echo "--- URL Generated ---\n";
        $url = $disk->url($storagePath);
        echo "URL: {$url}\n";
        echo "Test URL: <a href='{$url}' target='_blank'>Click to test</a>\n\n";
        
        echo "--- Check Symlink ---\n";
        $symlinkPath = __DIR__ . '/storage';
        if (is_link($symlinkPath)) {
            $target = readlink($symlinkPath);
            echo "Symlink exists: ✓ YES\n";
            echo "Target: {$target}\n";
        } else {
            echo "Symlink exists: ❌ NO\n";
            echo "Run: php artisan storage:link\n";
        }
        
        echo "\n--- HTML Inline Style ---\n";
        echo "Generated in Blade:\n";
        echo "background-image: url('{$url}');\n";
        echo "background-size: {$user->theme_bg_size};\n";
        echo "background-position: center center;\n";
        echo "background-attachment: fixed;\n";
        
    } else {
        echo "❌ NO BACKGROUND SET\n";
        echo "Upload a background image in the profile form and save.\n";
    }
}

echo "\n=== END DEBUG ===\n";
echo "</pre>";

// Optionally show raw database query
echo "<pre style='background:#222; color:#0f0; padding:20px; font-family:monospace; border-radius:8px; margin-top:20px;'>";
if ($user) {
    echo "Raw User Data:\n";
    echo json_encode($user->only(['id', 'name', 'email', 'theme_bg_path', 'theme_bg_size', 'theme_overlay']), JSON_PRETTY_PRINT);
}
echo "</pre>";
?>
