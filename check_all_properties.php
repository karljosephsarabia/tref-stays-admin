<?php
require 'vendor/autoload.php';

$db = new SQLite3('database/database.sqlite');

echo "=== All Properties Currency Values ===\n";
$result = $db->query("SELECT id, title, price, currency FROM rs_properties WHERE active = 1");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: {$row['id']}, Title: {$row['title']}, Price: {$row['price']}, Currency: " . ($row['currency'] ?? 'USD (default)') . "\n";
}
