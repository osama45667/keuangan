<?php

require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// Try to get current authenticated user
$mainUser = Auth::user();

echo "=== LARAVEL SETUP CHECK ===\n";
echo "Auth user (from guard): " . ($mainUser ? $mainUser->email : 'NOT SET') . "\n\n";

// Query all users with themes
$users = \App\Models\User::whereNotNull('theme_bg_path')->get();

if ($users->isEmpty()) {
    echo "No users with themes found in database!\n";
    exit;
}

echo "Found " . $users->count() . " user(s) with theme:\n\n";

foreach ($users as $user) {
    echo "--- User: " . $user->email . " ---\n";
    echo "Database fields:\n";
    echo "  theme_bg_path: " . ($user->theme_bg_path ?? 'NULL') . "\n";
    echo "  theme_overlay: " . ($user->theme_overlay ?? 'NULL') . "\n";
    echo "  theme_bg_size: " . ($user->theme_bg_size ?? 'NULL') . "\n";
    
    if ($user->theme_bg_path) {
        // File check
        $storagePath = storage_path('app/public/' . $user->theme_bg_path);
        echo "\nFile storage:\n";
        echo "  Path: " . $storagePath . "\n";
        echo "  Exists: " . (file_exists($storagePath) ? 'YES (' . filesize($storagePath) . ' bytes)' : 'NO') . "\n";
        
        // URL generation
        $url = Storage::disk('public')->url($user->theme_bg_path);
        echo "\nGenerated URLs:\n";
        echo "  Storage URL: " . $url . "\n";
        echo "  Manual URL: /storage/" . $user->theme_bg_path . "\n";
        
        // Check via public path
        $publicPath = public_path('storage/' . $user->theme_bg_path);
        echo "  Accessible via public: " . (file_exists($publicPath) ? 'YES' : 'NO') . "\n";
        
        // HTML inline style example
        $inlineStyle = "background-image: url('" . $url . "'); background-size: " . ($user->theme_bg_size ?? 'cover') . "; background-position: center; background-attachment: fixed; background-repeat: no-repeat;";
        echo "\nHTML that should be generated:\n";
        echo '<body class="app-body has-bg theme-overlay-' . ($user->theme_overlay ?? 'auto') . '" style="' . htmlspecialchars($inlineStyle) . '">';
        echo "\n</body>\n";
    }
    
    echo "\n";
}
?>
