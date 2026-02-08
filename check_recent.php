<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\RsUser;
use App\RsProperty;

echo "=== Recent Users ===\n";
$users = RsUser::orderBy('id', 'desc')->take(5)->get();
foreach($users as $u) {
    echo "ID: {$u->id} | {$u->first_name} {$u->last_name} | {$u->email} | Role: {$u->role_id} | Active: " . ($u->activated ? 'Yes' : 'No') . " | Created: {$u->created_at}\n";
}

echo "\n=== Recent Properties ===\n";
$properties = RsProperty::orderBy('id', 'desc')->take(5)->get();
foreach($properties as $p) {
    echo "ID: {$p->id} | {$p->title} | Owner: {$p->owner_id} | Status: {$p->status} | Active: " . ($p->is_active ? 'Yes' : 'No') . " | Created: {$p->created_at}\n";
}
