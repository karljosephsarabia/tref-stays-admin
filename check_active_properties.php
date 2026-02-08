<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Active Properties ===\n";
$properties = \SMD\Common\ReservationSystem\Models\RsProperty::with(['images'])
    ->where('active', true)
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get();

echo "Found " . $properties->count() . " active properties\n\n";

foreach($properties as $p) {
    echo "ID: {$p->id}\n";
    echo "  Title: {$p->title}\n";
    echo "  Owner ID: {$p->owner_id}\n";
    echo "  Active: " . ($p->active ? 'YES' : 'NO') . "\n";
    echo "  Price: {$p->price}\n";
    echo "  Address: {$p->map_address}\n";
    echo "  Images: " . $p->images->count() . "\n";
    echo "---\n";
}

// Check if your newest property is in there
echo "\n=== Properties by Owner 8 ===\n";
$myProps = \SMD\Common\ReservationSystem\Models\RsProperty::where('owner_id', 8)->get();
foreach($myProps as $p) {
    echo "ID: {$p->id} | Title: {$p->title} | Active: " . ($p->active ? 'YES' : 'NO') . "\n";
}
