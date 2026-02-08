<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check what role_ids exist in database
$roles = DB::table('rs_users')->select('role_id')->distinct()->get();
echo "Role IDs in database:\n";
foreach($roles as $r) { 
    echo '- Role ID: ' . $r->role_id . "\n"; 
}

// Check what RoleType::REGISTER validation expects
echo "\n\nRoleType::REGISTER array:\n";
print_r(\SMD\Common\ReservationSystem\Enums\RoleType::REGISTER);

echo "\n\nValidation string would be: 'in:" . join(',', \SMD\Common\ReservationSystem\Enums\RoleType::REGISTER) . "'\n";
echo "But form sends: role_id=2\n";
