<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Storage;

echo "=== BACKGROUND FEATURE TEST ===\n\n";

// 1. Check users
$userCount = User::count();
echo "1. Total users: $userCount\n";

if ($userCount === 0) {
    echo "   ❌ No users found! Need to create test user first.\n";
    echo "   Run: php artisan migrate:fresh --seed\n";
    exit;
}

// 2. Get first user
$user = User::first();
echo "\n2. First user:\n";
echo "   ID: " . $user->id . "\n";
echo "   Name: " . $user->name . "\n";
echo "   Email: " . $user->email . "\n";

// 3. Check theme data
echo "\n3. Theme data:\n";
echo "   theme_bg_path: " . ($user->theme_bg_path ?? 'NULL/NOT SET') . "\n";
echo "   theme_overlay: " . ($user->theme_overlay ?? 'NULL/AUTO') . "\n";
echo "   theme_bg_size: " . ($user->theme_bg_size ?? 'NULL/COVER') . "\n";

// 4. Check storage
echo "\n4. Storage check:\n";
$disk = Storage::disk('public');

if ($user->theme_bg_path) {
    $exists = $disk->exists($user->theme_bg_path);
    echo "   File exists: " . ($exists ? 'YES ✓' : 'NO ✗') . "\n";
    
    if ($exists) {
        $url = $disk->url($user->theme_bg_path);
        echo "   Storage URL: " . $url . "\n";
        
        // Check if file actually accessible
        $fullPath = storage_path('app/public/' . $user->theme_bg_path);
        $fileExists = file_exists($fullPath);
        echo "   File path: " . $fullPath . "\n";
        echo "   File accessible: " . ($fileExists ? 'YES ✓' : 'NO ✗') . "\n";
    }
} else {
    echo "   No background set for user\n";
}

// 5. Check symlink
echo "\n5. Symlink check:\n";
$symlink = public_path('storage');
$target = storage_path('app/public');
$isLink = is_link($symlink);
$linkTarget = $isLink ? readlink($symlink) : null;

echo "   Symlink exists: " . ($isLink ? 'YES ✓' : 'NO ✗') . "\n";
if ($isLink) {
    echo "   Target: " . $linkTarget . "\n";
    echo "   Correct: " . (strpos($linkTarget, 'storage') !== false ? 'YES ✓' : 'NO ✗') . "\n";
}

// 6. Test CSS rendering
echo "\n6. CSS rendering (what would be in Blade):\n";
if ($user->theme_bg_path) {
    $bgUrl = Storage::disk('public')->url($user->theme_bg_path);
    $bgSize = $user->theme_bg_size ?? 'cover';
    $overlay = $user->theme_overlay ?? 'auto';
    
    echo "   Blade inline style:\n";
    echo "   style=\"background-image: url('" . htmlspecialchars($bgUrl) . "'); background-size: $bgSize; ...\"\n";
    echo "   Class: has-bg theme-overlay-$overlay\n";
    echo "\n   CSS will apply:\n";
    echo "   .app-body.has-bg { background: none !important; }\n";
    echo "   .app-body.has-bg::before { overlay effect }\n";
    echo "   .app-body.has-bg .app-main { blur effect }\n";
} else {
    echo "   No background - no inline style applied\n";
}

// 7. Recommendations
echo "\n7. Status & Recommendations:\n";

if ($userCount > 0 && !$user->theme_bg_path) {
    echo "   ⚠️  User exists but NO background set\n";
    echo "   ACTION: Upload background image via profile page\n";
} elseif ($user->theme_bg_path) {
    $exists = Storage::disk('public')->exists($user->theme_bg_path);
    if ($exists) {
        echo "   ✓ Background set and file exists\n";
        echo "   ✓ Should be rendering now\n";
        echo "   ACTION: Check if background visible on profile page\n";
        echo "   If not visible: Check browser DevTools (F12) for CSS issues\n";
    } else {
        echo "   ✗ Background path set but FILE NOT FOUND\n";
        echo "   ACTION: Re-upload background image\n";
    }
}

echo "\n=== END TEST ===\n";
?>
