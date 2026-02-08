<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ALL Properties ===\n";
$props = DB::table('rs_properties')->get();
echo "Total: " . count($props) . "\n\n";

foreach($props as $p) {
    echo "ID: {$p->id}\n";
    echo "  Title: {$p->title}\n";
    echo "  Owner: {$p->owner_id}\n";
    echo "  Status: {$p->status}\n";
    echo "  Address: " . ($p->map_address ?? 'NULL') . "\n";
    
    // Check images
    $images = DB::table('rs_property_images')->where('property_id', $p->id)->count();
    echo "  Images: {$images}\n";
    echo "---\n";
}
