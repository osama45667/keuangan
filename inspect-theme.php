<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

echo "<!DOCTYPE html><html><head><title>Theme Inspect</title><style>";
echo "body { font-family: monospace; margin: 20px; background: #f5f5f5; }";
echo ".box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; }";
echo ".ok { color: green; font-weight: bold; } .error { color: red; font-weight: bold; }";
echo "</style></head><body>";

try {
    // Get all users
    $users = \App\Models\User::all();
    
    echo "<h2>All Users in Database</h2>";
    foreach ($users as $user) {
        echo "<div class='box'>";
        echo "<strong>User: " . $user->name . " (ID: " . $user->id . ")</strong><br>";
        echo "theme_bg_path: " . ($user->theme_bg_path ? "<span class='ok'>" . $user->theme_bg_path . "</span>" : "<span class='error'>NULL</span>") . "<br>";
        echo "theme_bg_size: " . ($user->theme_bg_size ?? 'NULL') . "<br>";
        echo "theme_overlay: " . ($user->theme_overlay ?? 'NULL') . "<br>";
        
        if ($user->theme_bg_path) {
            $disk = \Illuminate\Support\Facades\Storage::disk('public');
            $exists = $disk->exists($user->theme_bg_path);
            echo "File exists: " . ($exists ? "<span class='ok'>YES</span>" : "<span class='error'>NO</span>") . "<br>";
            
            if ($exists) {
                $url = $disk->url($user->theme_bg_path);
                echo "URL: <code>" . htmlspecialchars($url) . "</code><br>";
            }
        }
        echo "</div>";
    }
    
    if ($users->isEmpty()) {
        echo "<p class='error'>No users found!</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";
?>
