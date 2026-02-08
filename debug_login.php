<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\RsUser;
use Illuminate\Support\Facades\Hash;

// Get the most recent user
$user = RsUser::orderBy('id', 'desc')->first();
echo "Most recent user:\n";
echo "ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
echo "Password field: " . ($user->password ? substr($user->password, 0, 60) : 'NULL') . "\n";
echo "PIN: " . ($user->pin ?? 'NULL') . "\n";
echo "Activated: " . ($user->activated ? 'YES' : 'NO') . "\n";

// Check your specific user
$yourUser = RsUser::where('email', 'yyf706@gmail.com')->first();
if ($yourUser) {
    echo "\n\nYour user (yyf706@gmail.com):\n";
    echo "ID: " . $yourUser->id . "\n";
    echo "Password field: " . ($yourUser->password ? substr($yourUser->password, 0, 60) : 'NULL') . "\n";
    echo "PIN: " . ($yourUser->pin ?? 'NULL') . "\n";
    echo "Activated: " . ($yourUser->activated ? 'YES' : 'NO') . "\n";
    
    // Test password hash
    $testPassword = 'password123';
    echo "\nTesting Hash::check with '$testPassword': " . (Hash::check($testPassword, $yourUser->password) ? 'MATCH' : 'NO MATCH') . "\n";
}

// Show database column names for rs_users
echo "\n\nColumn names in rs_users table:\n";
$columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(rs_users)");
foreach ($columns as $col) {
    echo "- " . $col->name . " (" . $col->type . ")\n";
}
