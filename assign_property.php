<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Assign property ID 5 ("The Boca Haven Villa") to user ID 8
$property = \SMD\Common\ReservationSystem\Models\RsProperty::find(5);
if ($property) {
    $property->owner_id = 8;
    $property->save();
    echo "Property ID 5 assigned to user ID 8\n";
    echo "Title: " . $property->title . "\n";
}

// Also create a brand new property for this user
$newProperty = \SMD\Common\ReservationSystem\Models\RsProperty::create([
    'owner_id' => 8,
    'title' => 'My Vacation Home',
    'price' => 250.00,
    'guest_count' => 6,
    'bed_count' => 3,
    'bedroom_count' => 3,
    'bathroom_count' => 2,
    'property_type' => 2, // House
    'map_address' => '123 Beach Drive, Miami, FL 33101',
    'additional_information' => 'A beautiful vacation home near the beach.',
    'active' => true,
]);

echo "\nCreated new property:\n";
echo "ID: " . $newProperty->id . "\n";
echo "Title: " . $newProperty->title . "\n";

// Verify
echo "\nYour properties now:\n";
$props = \SMD\Common\ReservationSystem\Models\RsProperty::where('owner_id', 8)->get();
foreach ($props as $p) {
    echo "- ID " . $p->id . ": " . $p->title . " ($" . $p->price . "/night)\n";
}
