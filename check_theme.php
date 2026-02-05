<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = \App\Models\User::select('id', 'name', 'theme_bg_path', 'theme_overlay', 'theme_bg_size')->get();

echo "=== THEME DATA CHECK ===\n\n";
foreach($users as $u) {
    echo sprintf("User: %-20s | Path: %-50s | Overlay: %-6s | Size: %-8s\n", 
        $u->name, 
        $u->theme_bg_path ?? 'NULL', 
        $u->theme_overlay ?? 'NULL',
        $u->theme_bg_size ?? 'NULL'
    );
}

echo "\n=== STORAGE URL TEST ===\n";
$user = $users->first();
if($user && $user->theme_bg_path) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
    echo "User: " . $user->name . "\n";
    echo "Path: " . $user->theme_bg_path . "\n";
    echo "URL: " . $url . "\n";
    echo "File Exists: " . (file_exists(storage_path('app/public/' . $user->theme_bg_path)) ? 'YES' : 'NO') . "\n";
}
?>
