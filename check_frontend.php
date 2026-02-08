<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Models\RsProperty;

echo "=== Using RsProperty Model ===\n";
$count = RsProperty::where('active', true)->count();
echo "Active properties (Model): {$count}\n";

echo "\n=== Using Raw DB ===\n";
$dbCount = DB::table('rs_properties')->where('active', 1)->count();
echo "Active properties (DB): {$dbCount}\n";

echo "\n=== Properties with images ===\n";
$props = RsProperty::with(['images'])->where('active', true)->limit(5)->get();
foreach($props as $p) {
    echo "ID: {$p->id} | {$p->title} | Images: " . $p->images->count() . "\n";
}
