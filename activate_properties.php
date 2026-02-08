<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking Properties ===\n";
$props = DB::table('rs_properties')->get(['id', 'title', 'owner_id', 'is_active', 'map_address']);
echo "Total: " . count($props) . "\n\n";

$activeCount = 0;
$inactiveCount = 0;

foreach($props as $p) {
    if ($p->is_active) {
        $activeCount++;
    } else {
        $inactiveCount++;
    }
    echo "ID: {$p->id} | Active: " . ($p->is_active ? 'YES' : 'NO') . " | {$p->title}\n";
}

echo "\n\nActive: {$activeCount}, Inactive: {$inactiveCount}\n";

// Activate all properties
echo "\n=== Activating all properties... ===\n";
$updated = DB::table('rs_properties')->update(['is_active' => true]);
echo "Updated {$updated} properties to active!\n";
