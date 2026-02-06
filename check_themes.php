<?php
require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = \Illuminate\Http\Request::capture());

$users = \App\Models\User::where('theme_bg_path', '!=', null)->get();

echo "Users with theme set:\n";
foreach ($users as $user) {
    echo "\nUser: " . $user->name . " (ID: " . $user->id . ")\n";
    echo "  theme_bg_path: " . ($user->theme_bg_path ?? 'NULL') . "\n";
    echo "  theme_bg_size: " . ($user->theme_bg_size ?? 'NULL') . "\n";
    echo "  theme_overlay: " . ($user->theme_overlay ?? 'NULL') . "\n";
    
    if ($user->theme_bg_path) {
        try {
            $url = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
            echo "  Generated URL: " . $url . "\n";
            $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($user->theme_bg_path);
            echo "  File exists: " . ($exists ? 'YES' : 'NO') . "\n";
        } catch (Exception $e) {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
}

if (count($users) === 0) {
    echo "No users with theme set.\nAll users:\n";
    \App\Models\User::all()->each(function ($user) {
        echo "- " . $user->name . " (ID: " . $user->id . ")\n";
    });
}
?>
