<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\RsUser;
use Illuminate\Support\Facades\Hash;
use SMD\Common\ReservationSystem\Enums\RoleType;

// Simulate login
$email = 'admin@ivrreservation.com';
$password = 'admin123456';

echo "=== Testing Login Process ===\n\n";

echo "Step 1: Finding user by email...\n";
$user = RsUser::where('email', $email)->first();

if (!$user) {
    echo "✗ User not found!\n";
    exit;
}

echo "✓ User found: {$user->email}\n\n";

echo "Step 2: Checking password...\n";
$passwordMatch = Hash::check($password, $user->password);
echo "Password match: " . ($passwordMatch ? "✓ Yes" : "✗ No") . "\n\n";

echo "Step 3: Checking role...\n";
echo "User role_id: '{$user->role_id}'\n";
echo "RoleType::ADMIN constant: '" . RoleType::ADMIN . "'\n";
echo "Role match: " . ($user->role_id === RoleType::ADMIN ? "✓ Yes" : "✗ No") . "\n\n";

echo "Step 4: Checking activation status...\n";
echo "Activated: " . ($user->activated ? "✓ Yes" : "✗ No") . "\n";
echo "Active: " . ($user->active ? "✓ Yes" : "✗ No") . "\n\n";

if ($passwordMatch && $user->role_id === RoleType::ADMIN && $user->activated && $user->active) {
    echo "=== ✓✓✓ ALL CHECKS PASSED ✓✓✓ ===\n";
    echo "Login should work with these credentials!\n";
} else {
    echo "=== ✗✗✗ SOME CHECKS FAILED ✗✗✗ ===\n";
    echo "There's an issue preventing login.\n";
}

echo "\n";
echo "Login URL: http://127.0.0.1:8000/admin/login\n";
echo "Email: admin@ivrreservation.com\n";
echo "Password: admin123456\n";
