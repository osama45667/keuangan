<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

echo "=== Database Info ===\n";
echo "DB Driver: " . DB::getDriverName() . "\n";

echo "\n=== First User Data ===\n";
$user = User::first();
if ($user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "theme_bg_path: " . ($user->theme_bg_path ?: "[NULL]") . "\n";
    echo "theme_bg_size: " . ($user->theme_bg_size ?: "[NULL]") . "\n";
    echo "theme_overlay: " . ($user->theme_overlay ?: "[NULL]") . "\n";
    
    if ($user->theme_bg_path) {
        echo "\n=== File Storage Check ===\n";
        $disk = Storage::disk('public');
        $path = 'themes/' . basename($user->theme_bg_path);
        echo "Expected path: themes/" . basename($user->theme_bg_path) . "\n";
        echo "Full path: " . storage_path("app/public/{$path}") . "\n";
        echo "File exists: " . (file_exists(storage_path("app/public/{$path}")) ? "✓ YES" : "✗ NO") . "\n";
        
        if (file_exists(storage_path("app/public/{$path}"))) {
            $size = filesize(storage_path("app/public/{$path}"));
            echo "File size: " . ($size / 1024) . " KB\n";
        }
        
        echo "Generated URL: " . $disk->url($path) . "\n";
    } else {
        echo "\n⚠ No background set - theme_bg_path is NULL\n";
    }
} else {
    echo "No users in database\n";
}
?>
