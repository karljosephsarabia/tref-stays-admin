<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Adding missing column is_paused to rs_properties...\n";

try {
    DB::statement('ALTER TABLE rs_properties ADD COLUMN is_paused INTEGER DEFAULT 0');
    echo "SUCCESS: Added is_paused column!\n";
} catch (\Exception $e) {
    echo "Column may already exist or error: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
