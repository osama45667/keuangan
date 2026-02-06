<?php
// Debug: Check if theme URL is accessible and what's being generated

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Http\Kernel::class)->handle(
    $request = \Illuminate\Http\Request::capture()
);

$user = auth()->user();

if (!$user) {
    die('Not authenticated. Please login first.');
}

echo "<h2>Theme Debug Info</h2>";
echo "<pre>";
echo "User: " . $user->name . "\n";
echo "theme_bg_path in DB: " . ($user->theme_bg_path ?? 'NULL') . "\n";
echo "theme_bg_size in DB: " . ($user->theme_bg_size ?? 'NULL') . "\n";
echo "theme_overlay in DB: " . ($user->theme_overlay ?? 'NULL') . "\n";

if ($user->theme_bg_path) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
    echo "\nGenerated URL: " . $url . "\n";
    echo "Full URL: " . url($url) . "\n";
    
    // Check if file exists
    $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($user->theme_bg_path);
    echo "File exists in storage: " . ($exists ? 'YES' : 'NO') . "\n";
    
    // Check if accessible via public/storage
    $public_path = public_path('storage/' . basename($user->theme_bg_path));
    echo "Public path check: " . ($public_path) . "\n";
    echo "Accessible via web: " . ((file_exists($public_path) || is_link(public_path('storage'))) ? 'YES' : 'NO (symlink missing?)') . "\n";
} else {
    echo "\nNo theme set\n";
}

echo "\nCSS Rules to Apply:\n";
if ($user->theme_bg_path) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
    echo "background-image: url('" . $url . "');\n";
    echo "background-size: " . ($user->theme_bg_size ?? 'cover') . ";\n";
    echo "Class: has-bg theme-overlay-" . ($user->theme_overlay ?? 'auto') . "\n";
}

echo "</pre>";

echo "<h2>Test CSS Rendering</h2>";
if ($user->theme_bg_path) {
    $url = \Illuminate\Support\Facades\Storage::disk('public')->url($user->theme_bg_path);
    $size = $user->theme_bg_size ?? 'cover';
    $overlay = $user->theme_overlay ?? 'auto';
    
    echo "<div style='
        width: 100%;
        min-height: 200px;
        background-image: url(\"" . $url . "\");
        background-size: " . $size . ";
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        border: 2px solid red;
    '>";
    echo "<div style='background: rgba(255,255,255,0.75); padding: 20px; backdrop-filter: blur(10px);'>";
    echo "If you see background image behind this box, CSS is working!";
    echo "</div>";
    echo "</div>";
} else {
    echo "No theme uploaded yet.";
}
?>
