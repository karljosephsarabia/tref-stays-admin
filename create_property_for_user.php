<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Models\RsProperty;

// Create property for user ID 10
$property = RsProperty::create([
    'owner_id' => 10,
    'title' => 'Shoels Vila',
    'property_type' => 'house',
    'bedroom_count' => 2,
    'bathroom_count' => 2,
    'guest_count' => 22,
    'price' => 222,
    'street_name' => '17 a cazonove mansions',
    'city' => 'London',
    'state' => 'London',
    'country' => 'uk',
    'zipcode_id' => 'N16 6AR',
    'map_address' => '17a Cazenove Mansions, London, UK, N16 6AR',
    'additional_information' => 'Hi, we are still in the middle of designing the mobile app before we can move forward. In the meantime, I have two more customizations to discuss. Can we jump on a Zoom call to go over them?',
    'amenities' => json_encode(['WiFi', 'Heating', 'Free Parking', 'Pool']),
    'kosher_info' => json_encode([
        'kosher_kitchen' => true,
        'shabbos_friendly' => true,
        'nearby_shul' => ['name' => 'satmer', 'distance' => '5 min drive'],
        'nearby_kosher_shops' => ['name' => 'kliens', 'distance' => '50 minutes walk'],
        'nearby_mikva' => ['name' => '', 'distance' => '']
    ]),
    'active' => 1,
    'is_paused' => 0
]);

echo "Property created successfully!\n";
echo "Property ID: " . $property->id . "\n";
echo "Title: " . $property->title . "\n";
echo "Owner ID: " . $property->owner_id . "\n";
