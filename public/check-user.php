<?php
require '../vendor/autoload.php';
$app = require_once '../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$count = User::count();
echo "Total users: " . $count . "\n";

if ($count > 0) {
    $user = User::first();
    echo "First user:\n";
    echo "  ID: " . $user->id . "\n";
    echo "  Name: " . $user->name . "\n";
    echo "  Email: " . $user->email . "\n";
    echo "  Theme BG Path: " . ($user->theme_bg_path ?? 'NOT SET') . "\n";
} else {
    echo "No users found. Please create a user first.\n";
}
?>
