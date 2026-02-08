<?php
require 'vendor/autoload.php';

$db = new SQLite3('database/database.sqlite');

// Check table structure
echo "=== rs_properties Table Structure ===\n";
$result = $db->query('PRAGMA table_info(rs_properties)');
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo $row['name'] . ' | ' . $row['type'] . "\n";
}

echo "\n=== Property ID 38 (Your Property) ===\n";
$result = $db->query("SELECT id, title, price, currency, daily_price FROM rs_properties WHERE id = 38");
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
} else {
    echo "Property 38 not found\n";
}

echo "\n=== All Properties Currency Values ===\n";
$result = $db->query("SELECT id, title, price, currency, daily_price FROM rs_properties");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: {$row['id']}, Title: {$row['title']}, Price: {$row['price']}, Currency: {$row['currency']}, Daily: {$row['daily_price']}\n";
}
