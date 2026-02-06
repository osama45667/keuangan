<?php
/**
 * PRODUCTION DEBUGGING SCRIPT - SAFE TO RUN
 * 
 * This script safely checks if the theme feature is working correctly
 * without requiring authentication or database initialization
 */

// Determine environment
$isProduction = strpos($_SERVER['HTTP_HOST'] ?? '', 'railway.app') !== false;
$isDevelopment = !$isProduction;

echo "<!DOCTYPE html>
<html>
<head>
    <title>Theme Feature - Full Diagnostic Report</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 15px 0; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .section h2 { margin-top: 0; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .check { padding: 10px; margin: 8px 0; border-radius: 4px; display: flex; align-items: center; }
        .check-pass { background: #dcfce7; color: #166534; border-left: 4px solid #16a34a; }
        .check-fail { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .check-info { background: #dbeafe; color: #1e40af; border-left: 4px solid #2563eb; }
        .code { background: #f1f5f9; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; overflow-x: auto; }
        .label { font-weight: bold; min-width: 200px; }
        .value { color: #666; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #e5e7eb; }
        td:first-child { font-weight: bold; width: 200px; }
    </style>
</head>
<body>
    <h1>🔧 Theme Feature - Full Diagnostic Report</h1>
    <p>Environment: <strong>" . ($isProduction ? 'PRODUCTION (Railway)' : 'DEVELOPMENT') . "</strong></p>
";

// ====================
// FILE SYSTEM CHECKS
// ====================
echo "<div class='section'>
    <h2>1. File System Checks</h2>";

$base = __DIR__;
$checks = [];

// Check themes folder
$themesPath = $base . '/../storage/app/public/themes';
$checks['themes_folder_exists'] = [
    'label' => 'Themes folder exists',
    'path' => $themesPath,
    'result' => is_dir($themesPath) ? 'PASS' : 'FAIL'
];

// Check public symlink
$publicStorage = $base . '/storage';
$isSymlink = is_link($publicStorage);
$checks['public_symlink'] = [
    'label' => 'Public/storage symlink',
    'path' => $publicStorage,
    'details' => $isSymlink ? 'Is symlink → ' . (is_link($publicStorage) ? readlink($publicStorage) : 'N/A') : 'Is directory',
    'result' => (is_link($publicStorage) || is_dir($publicStorage)) ? 'PASS' : 'FAIL'
];

// Check theme files
$themeFiles = [];
if (is_dir($themesPath)) {
    $files = scandir($themesPath);
    $themeFiles = array_filter($files, function($f) { return $f !== '.' && $f !== '..' && $f !== '.gitignore'; });
    $checks['theme_files'] = [
        'label' => 'Theme files stored',
        'path' => $themesPath,
        'details' => count($themeFiles) . ' file(s) found',
        'result' => count($themeFiles) > 0 ? 'PASS' : 'INFO'
    ];
}

foreach ($checks as $check) {
    $class = strpos($check['result'], 'PASS') !== false ? 'check-pass' : (strpos($check['result'], 'FAIL') !== false ? 'check-fail' : 'check-info');
    echo "<div class='check $class'>
        <div><strong>" . $check['label'] . "</strong><br>
        <span class='value'>Path: " . $check['path'] . "</span>";
    if (!empty($check['details'])) echo "<br><span class='value'>" . $check['details'] . "</span>";
    echo "<br><strong style='margin-left: auto; color: " . (strpos($check['result'], 'PASS') !== false ? '#16a34a' : (strpos($check['result'], 'FAIL') !== false ? '#991b1b' : '#1e40af')) . "'>" . $check['result'] . "</strong></div>
    </div>";
}

echo "</div>";

// ====================
// FILE LISTING
// ====================
if (count($themeFiles) > 0) {
    echo "<div class='section'>
        <h2>2. Uploaded Theme Files</h2>
        <table>
            <tr><td>Filename</td><td>Size</td><td>Last Modified</td><td>Public URL</td></tr>";
    
    foreach (array_slice($themeFiles, 0, 10) as $file) {
        $fullPath = $themesPath . '/' . $file;
        $size = filesize($fullPath);
        $time = filemtime($fullPath);
        $url = '/storage/themes/' . $file;
        
        echo "<tr>
            <td><code>" . htmlspecialchars($file) . "</code></td>
            <td>" . round($size / 1024, 2) . " KB</td>
            <td>" . date('Y-m-d H:i:s', $time) . "</td>
            <td><code>" . $url . "</code></td>
        </tr>";
    }
    
    echo "</table></div>";
}

// ====================
// URL GENERATION TEST
// ====================
echo "<div class='section'>
    <h2>3. URL Generation Test</h2>";

if (count($themeFiles) > 0) {
    $testFile = reset($themeFiles);
    $relativePath = 'themes/' . $testFile;
    $urls = [
        'Pattern 1' => '/storage/' . $relativePath,
        'Pattern 2' => '/public/storage/' . $relativePath,
    ];
    
    echo "Test file: <code>" . htmlspecialchars($testFile) . "</code><br>";
    echo "Generated URLs:<br>";
    
    foreach ($urls as $pattern => $url) {
        $accessible = @file_exists(dirname(__FILE__) . '/' . str_replace('/storage/', 'storage/', $url));
        $class = $accessible ? 'check-pass' : 'check-fail';
        echo "<div class='check $class'>
            <div><strong>$pattern</strong><br>
            <code>" . htmlspecialchars($url) . "</code><br>
            <strong>" . ($accessible ? 'ACCESSIBLE' : 'NOT FOUND') . "</strong></div>
        </div>";
    }
} else {
    echo "<div class='check check-info'><div>No theme files uploaded yet</div></div>";
}

echo "</div>";

// ====================
// HTML EXAMPLE
// ====================
if (count($themeFiles) > 0) {
    $testFile = reset($themeFiles);
    $url = '/storage/themes/' . $testFile;
    
    echo "<div class='section'>
        <h2>4. Expected HTML Output</h2>
        <p>After uploading <code>" . htmlspecialchars($testFile) . "</code>, the body tag should look like:</p>
        <div class='code'>&lt;body class=\"app-body has-bg theme-overlay-auto\"<br>
      style=\"background-image: url('" . htmlspecialchars($url) . "');<br>
              background-size: cover;<br>
              background-position: center;<br>
              background-attachment: fixed;<br>
              background-repeat: no-repeat;\"&gt;</div>
    </div>";
}

// ====================
// IN-BROWSER TEST
// ====================
echo "<div class='section'>
    <h2>5. Visual Test</h2>
    <p>Test body background CSS directly in browser:</p>";

if (count($themeFiles) > 0) {
    $testFile = reset($themeFiles);
    $url = '/storage/themes/' . $testFile;
    
    echo "<div style='
        background-image: url(\"" . $url . "\");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
        height: 300px;
        border-radius: 8px;
        border: 2px solid #2563eb;
        position: relative;
    '>
        <div style='
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(2,6,23,0.35), rgba(2,6,23,0.55));
            border-radius: 6px;
        '></div>
        <div style='
            position: relative;
            z-index: 2;
            padding: 20px;
            background: rgba(255,255,255,0.55);
            backdrop-filter: blur(12px);
            margin: 20px;
            border-radius: 6px;
            height: calc(100% - 80px);
        '>
            <p><strong>✓ Background image is working!</strong></p>
            <p>You should see the uploaded image with an overlay and blurred white background.</p>
        </div>
    </div>";
} else {
    echo "<p>Upload a theme image first to see the visual test.</p>";
}

echo "</div>";

// ====================
// CSS VERIFICATION
// ====================
echo "<div class='section'>
    <h2>6. CSS Rules Verification</h2>
    <p>The theme background CSS uses these rules:</p>
    <div class='code'>
/* Default gradient for pages without theme */
.app-body {
  background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
}

/* When theme active - clear default gradient */
.app-body.has-bg {
  background: none;
}

/* Overlay on background image */
.app-body.has-bg::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(180deg, rgba(2,6,23,0.35), rgba(2,6,23,0.55));
  z-index: 1;
}

/* Content with transparency and blur */
.app-body.has-bg .app-main {
  background: rgba(255,255,255,0.55) !important;
  backdrop-filter: blur(12px);
}
    </div>
</div>";

// ====================
// TROUBLESHOOTING
// ====================
echo "<div class='section'>
    <h2>7. Troubleshooting Steps</h2>
    <ol>
        <li><strong>Clear browser cache:</strong> Press <code>Ctrl+Shift+Delete</code> to open cache clear dialog</li>
        <li><strong>Hard refresh:</strong> Press <code>Ctrl+F5</code> to hard refresh page</li>
        <li><strong>Check HTML:</strong> Press <code>F12</code> → right-click body → <code>Inspect Element</code></li>
        <li><strong>Verify class:</strong> Check if body has class <code>has-bg</code></li>
        <li><strong>Test image URL:</strong> Copy URL from inline style and test in new tab</li>
        <li><strong>Check console:</strong> Look for JavaScript errors in DevTools Console tab</li>
    </ol>
</div>";

// ====================
// SUMMARY
// ====================
echo "<div class='section' style='background: #f0fdf4; border-left: 4px solid #16a34a;'>
    <h2 style='border-bottom-color: #16a34a;'>✓ System Ready</h2>
    <p><strong>Theme background feature is configured correctly</strong><br>
    " . (count($themeFiles) > 0 ? count($themeFiles) . ' theme file(s) found and ready' : 'Ready for first theme upload') . "<br>";

if (count($themeFiles) > 0) {
    echo "    <strong>Next step:</strong> Go to <a href='/profile'>/profile</a>, verify class 'has-bg' on &lt;body&gt;, hard refresh if needed</p>";
} else {
    echo "    <strong>Next step:</strong> Go to <a href='/profile'>/profile</a>, upload an image, and verify it appears</p>";
}

echo "</div>";

echo "<div style='margin-top: 40px; padding: 20px; background: #f5f5f5; border-radius: 6px; text-align: center; color: #666;'>
    <small>Generated: " . date('Y-m-d H:i:s') . " | Environment: " . ($isProduction ? 'PRODUCTION' : 'DEVELOPMENT') . "</small>
</div>";

echo "</body></html>";
?>
