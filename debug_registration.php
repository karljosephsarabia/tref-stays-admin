<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Recent Users ===\n";
$users = \App\RsUser::orderBy('id', 'desc')->take(5)->get();
foreach($users as $u) {
    echo "ID: {$u->id} | Email: {$u->email} | Activated: {$u->activated} | Role: {$u->role_id}\n";
    echo "   Password hash: " . substr($u->password, 0, 20) . "...\n";
}

echo "\n=== Recent Properties ===\n";
$properties = \SMD\Common\ReservationSystem\Models\RsProperty::orderBy('id', 'desc')->take(5)->get();
foreach($properties as $p) {
    echo "ID: {$p->id} | Owner: {$p->owner_id} | Title: {$p->title} | Active: {$p->active}\n";
}

// Check the login logic
echo "\n=== Login Test ===\n";
$lastUser = \App\RsUser::orderBy('id', 'desc')->first();
if ($lastUser) {
    echo "Testing last user: {$lastUser->email}\n";
    echo "Activated status: " . ($lastUser->activated ? 'YES' : 'NO') . "\n";
    
    // Check if password verification works
    $testPassword = 'test123'; // Try a common password
    if (\Illuminate\Support\Facades\Hash::check($testPassword, $lastUser->password)) {
        echo "Password 'test123' matches!\n";
    } else {
        echo "Password 'test123' does NOT match\n";
    }
}
