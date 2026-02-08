<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\RsUser;
use Illuminate\Support\Facades\Hash;

echo "Checking admin user...\n\n";

$user = RsUser::where('email', 'admin@ivrreservation.com')->first();

if ($user) {
    echo "✓ User found!\n";
    echo "  Email: " . $user->email . "\n";
    echo "  Role ID: " . $user->role_id . "\n";
    echo "  Activated: " . ($user->activated ? 'Yes' : 'No') . "\n";
    echo "  Active: " . ($user->active ? 'Yes' : 'No') . "\n";
    echo "  Password hash: " . substr($user->password, 0, 20) . "...\n\n";
    
    // Test password
    echo "Testing password 'admin123456'...\n";
    if (Hash::check('admin123456', $user->password)) {
        echo "✓ Password is correct!\n";
    } else {
        echo "✗ Password does not match!\n";
        echo "Creating new password hash...\n";
        $user->password = Hash::make('admin123456');
        $user->save();
        echo "✓ Password updated!\n";
    }
} else {
    echo "✗ User NOT found. Creating admin user...\n\n";
    
    $user = new RsUser();
    $user->first_name = 'System';
    $user->last_name = 'Administrator';
    $user->email = 'admin@ivrreservation.com';
    $user->password = Hash::make('admin123456');
    $user->role_id = 'admin';
    $user->phone_number = '1234567890';
    $user->pin = '1234';
    $user->active = true;
    $user->activated = true;
    $user->save();
    
    echo "✓ Admin user created successfully!\n";
    echo "  Email: admin@ivrreservation.com\n";
    echo "  Password: admin123456\n";
}

echo "\n=== You can now login with these credentials ===\n";
echo "Email: admin@ivrreservation.com\n";
echo "Password: admin123456\n";
