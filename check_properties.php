<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = \App\RsUser::where('email', 'yyf706@gmail.com')->first();
echo "User ID: " . ($u ? $u->id : 'not found') . "\n";

if ($u) {
    $props = \SMD\Common\ReservationSystem\Models\RsProperty::where('owner_id', $u->id)->get();
    echo "Properties: " . $props->count() . "\n";
    foreach ($props as $p) {
        echo "- ID " . $p->id . ": " . $p->title . "\n";
    }
    
    // Also check all properties in the system
    echo "\nAll properties in system:\n";
    $allProps = \SMD\Common\ReservationSystem\Models\RsProperty::all();
    echo "Total: " . $allProps->count() . "\n";
    foreach ($allProps as $p) {
        echo "- ID " . $p->id . " (owner_id: " . $p->owner_id . "): " . $p->title . "\n";
    }
}
