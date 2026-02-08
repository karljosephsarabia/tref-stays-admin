<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Column names in rs_properties ===\n";
$cols = DB::select("PRAGMA table_info(rs_properties)");
foreach($cols as $c) {
    echo "- " . $c->name . "\n";
}

echo "\n=== First property data ===\n";
$prop = DB::table('rs_properties')->first();
if ($prop) {
    print_r($prop);
}
