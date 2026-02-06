<?php
// Simple diagnostic - no Laravel init needed
$base = __DIR__;

echo "=== FILE STORAGE DIAGNOSTIC ===\n\n";

// Check themes folder
$themesPath = $base . '/../storage/app/public/themes';
echo "Themes folder: " . $themesPath . "\n";
echo "Exists: " . (is_dir($themesPath) ? 'YES' : 'NO') . "\n";

if (is_dir($themesPath)) {
    $files = scandir($themesPath);
    $files = array_filter($files, function($f) { return $f !== '.' && $f !== '..'; });
    echo "Files found: " . count($files) . "\n";
    foreach ($files as $file) {
        $fullPath = $themesPath . '/' . $file;
        echo "  - " . $file . " (" . filesize($fullPath) . " bytes)\n";
    }
}

echo "\n=== PUBLIC SYMLINK CHECK ===\n";
$publicStorage = $base . '/storage';
echo "Public storage symlink: " . $publicStorage . "\n";
if (is_link($publicStorage)) {
    echo "Is symlink: YES\n";
    echo "Points to: " . readlink($publicStorage) . "\n";
} else {
    echo "Is symlink: NO (checking if DIR)\n";
    echo "Is directory: " . (is_dir($publicStorage) ? 'YES' : 'NO') . "\n";
}

// Test URLs
echo "\n=== URL GENERATION TEST ===\n";
if (count($files) > 0) {
    $testFile = reset($files);
    $url1 = '/storage/themes/' . $testFile;
    $url2 = '/public/storage/themes/' . $testFile;
    
    echo "Test file: " . $testFile . "\n";
    echo "URL pattern 1: " . $url1 . "\n";
    echo "URL pattern 2: " . $url2 . "\n";
    echo "  -> Accessible via public/storage: " . (file_exists($publicStorage . '/themes/' . $testFile) ? 'YES' : 'NO') . "\n";
}

echo "\n=== HTML EXAMPLE ===\n";
if (count($files) > 0) {
    $testFile = reset($files);
    $url = '/storage/themes/' . $testFile;
    echo '<body class="app-body has-bg theme-overlay-auto" style="background-image: url(\'' . $url . '\'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat;">';
    echo "\n</body>";
}
?>
