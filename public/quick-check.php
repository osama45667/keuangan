<?php
// Check if user is authenticated and what background data they have
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// Try to get auth user
auth()->loginUsingId(1); // For testing - login as first user

$user = auth()->user();

if (!$user) {
    echo "<h2>❌ No user authenticated</h2>";
    echo "Please login first at /login";
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Quick Background Check</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .ok { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        pre { background: #f8f8f8; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .button { display: inline-block; background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 10px 0; }
        .button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Quick Background Check</h1>

        <h2>Current User: <?php echo htmlspecialchars($user->name); ?></h2>

        <h3>Background Status</h3>
        <?php if ($user->theme_bg_path): ?>
            <p class="ok">✓ Background is SET</p>
            <p><strong>Path:</strong> <?php echo htmlspecialchars($user->theme_bg_path); ?></p>
            <p><strong>Overlay:</strong> <?php echo htmlspecialchars($user->theme_overlay ?? 'auto'); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($user->theme_bg_size ?? 'cover'); ?></p>

            <?php
            $exists = Storage::disk('public')->exists($user->theme_bg_path);
            $url = Storage::disk('public')->url($user->theme_bg_path);
            ?>

            <p><strong>File exists:</strong> <span class="<?php echo $exists ? 'ok' : 'error'; ?>">
                <?php echo $exists ? '✓ YES' : '✗ NO'; ?>
            </span></p>

            <p><strong>Storage URL:</strong></p>
            <pre><?php echo htmlspecialchars($url); ?></pre>

            <h3>What should happen:</h3>
            <ol>
                <li>HTML body tag should have inline style: <code>background-image: url('<?php echo htmlspecialchars($url); ?>')</code></li>
                <li>HTML body tag should have classes: <code>has-bg theme-overlay-<?php echo htmlspecialchars($user->theme_overlay ?? 'auto'); ?></code></li>
                <li>CSS should render this background with overlay & blur effect</li>
                <li>Page should show background image across entire page</li>
            </ol>

            <h3>To verify:</h3>
            <ol>
                <li>Open <strong>F12 DevTools</strong> → Elements tab</li>
                <li>Look for body tag: Should have inline style + classes</li>
                <li>If you see background image URL in inline style: <span class="ok">CSS rendering should work</span></li>
                <li>If you don't see background: <span class="error">Try hard refresh (Ctrl+Shift+R or Cmd+Shift+R)</span></li>
            </ol>

        <?php else: ?>
            <p class="warning">⚠️  No background set yet</p>
            <p>Please:</p>
            <ol>
                <li>Go to <a href="/profile" class="button">Profile Page</a></li>
                <li>Upload a background image</li>
                <li>Click "Save Changes"</li>
                <li>Return here to check status</li>
            </ol>
        <?php endif; ?>

        <h3>Quick Actions</h3>
        <a href="/profile" class="button">→ Go to Profile</a>
        <a href="javascript:location.reload()" class="button">↻ Refresh This Page</a>

    </div>
</body>
</html>
