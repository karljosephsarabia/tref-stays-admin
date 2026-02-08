<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\RsUser;
use Illuminate\Support\Facades\DB;

echo "=== Most Recent Users (last 10) ===\n";
$users = RsUser::orderBy('id', 'desc')->take(10)->get();
foreach($users as $u) {
    echo "ID: {$u->id} | {$u->first_name} {$u->last_name} | {$u->email} | Role: {$u->role_id} | Active: " . ($u->activated ? 'Yes' : 'No') . " | Created: {$u->created_at}\n";
}

echo "\n=== Most Recent Properties (last 10) ===\n";
$props = DB::table('rs_properties')->orderBy('id', 'desc')->take(10)->get(['id', 'title', 'owner_id', 'active', 'created_at']);
foreach($props as $p) {
    echo "ID: {$p->id} | {$p->title} | Owner: {$p->owner_id} | Active: " . ($p->active ? 'Yes' : 'No') . " | Created: {$p->created_at}\n";
}

echo "\n=== Check Laravel Logs (last 50 lines) ===\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    foreach($lastLines as $line) {
        echo $line;
    }
} else {
    echo "No log file found.\n";
}
