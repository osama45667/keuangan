<?php
/**
 * Theme Debug Page
 * Safely check theme configuration without complex request handling
 */

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head><title>Theme Debug</title><style>";
echo "body { font-family: sans-serif; margin: 20px; }";
echo ".success { color: green; } .error { color: red; } .warning { color: orange; }";
echo "pre { background: #f5f5f5; padding: 15px; border-radius: 5px; overflow: auto; }";
echo "</style></head>";
echo "<body>";

echo "<h2>🔧 Theme Debug Information</h2>";

try {
    // Load Laravel
    require __DIR__ . '/../vendor/autoload.php';
    
    // Bootstrap Laravel app
    $app = require __DIR__ . '/../bootstrap/app.php';
    
    // Get first user from database (simplest approach)
    /** @var \App\Models\User|null $user */
    $user = \App\Models\User::first();
    
    if (!$user) {
        echo '<p class="error">❌ No users found in database. Please create an account first.</p>';
        exit;
    }
    
    echo '<h3>User Information</h3>';
    echo '<pre>';
    echo "Name: " . htmlspecialchars($user->name) . "\n";
    echo "Email: " . htmlspecialchars($user->email) . "\n";
    echo "</pre>";
    
    echo '<h3>Database - Theme Fields</h3>';
    echo '<pre>';
    echo "theme_bg_path: " . (empty($user->theme_bg_path) ? '<span class="warning">NOT SET</span>' : '<span class="success">' . htmlspecialchars($user->theme_bg_path) . '</span>') . "\n";
    echo "theme_bg_size: " . htmlspecialchars($user->theme_bg_size ?? 'cover') . "\n";
    echo "theme_overlay: " . htmlspecialchars($user->theme_overlay ?? 'auto') . "\n";
    echo "</pre>";
    
    if ($user->theme_bg_path) {
        echo '<h3>File Storage Check</h3>';
        echo '<pre>';
        
        // Check if file exists in storage
        $storage = \Illuminate\Support\Facades\Storage::disk('public');
        $exists = $storage->exists($user->theme_bg_path);
        
        echo "File path in storage: " . htmlspecialchars($user->theme_bg_path) . "\n";
        echo "Exists in storage: " . ($exists ? '<span class="success">✓ YES</span>' : '<span class="error">✗ NO</span>') . "\n";
        
        // Generate URL using Storage facade
        // @phpstan-ignore-next-line
        $url = $storage->url($user->theme_bg_path);
        echo "Generated URL: " . htmlspecialchars($url) . "\n";
        
        // Try to get file info
        if ($exists) {
            $size = $storage->size($user->theme_bg_path);
            $sizeKB = round($size / 1024, 2);
            echo "File size: " . $sizeKB . " KB\n";
        }
        
        echo "</pre>";
        
        echo '<h3>CSS Inline Style (as rendered in HTML)</h3>';
        echo '<pre>';
        echo "style=\"\n";
        echo "  background-image: url('" . htmlspecialchars($url) . "');\n";
        echo "  background-size: " . htmlspecialchars($user->theme_bg_size ?? 'cover') . ";\n";
        echo "  background-position: center;\n";
        echo "  background-attachment: fixed;\n";
        echo "  background-repeat: no-repeat;\n";
        echo "\"";
        echo "</pre>";
        
        echo '<h3>CSS Class Names</h3>';
        echo '<pre>';
        echo 'class="app-body has-bg theme-overlay-' . htmlspecialchars($user->theme_overlay ?? 'auto') . '"';
        echo '</pre>';
        
        echo '<h3>✅ Visual Test</h3>';
        echo '<div style="'
            . 'width: 100%; '
            . 'height: 300px; '
            . 'border: 3px solid red; '
            . 'margin: 20px 0; '
            . 'background-image: url(\'' . htmlspecialchars($url) . '\'); '
            . 'background-size: cover; '
            . 'background-position: center; '
            . 'background-attachment: fixed; '
            . 'background-repeat: no-repeat;'
            . '">';
            echo '<div style="'
                . 'background: rgba(255,255,255,0.65); '
                . 'padding: 20px; '
                . 'backdrop-filter: blur(8px); '
                . 'height: 100%;'
                . '">';
                echo '<p style="color: #333; margin: 0;">If you see the uploaded image behind this box, CSS rendering is working! ✓</p>';
            echo '</div>';
        echo '</div>';
        
    } else {
        echo '<p class="warning">⚠️ No theme image set in database.</p>';
    }
    
} catch (\Exception $e) {
    echo '<h3 class="error">❌ Error</h3>';
    echo '<pre class="error">';
    echo htmlspecialchars($e->getMessage()) . "\n\n";
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}

echo '</body>';
echo '</html>';
?>
