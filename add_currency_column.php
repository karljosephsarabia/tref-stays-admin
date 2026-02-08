<?php
require 'vendor/autoload.php';

$db = new SQLite3('database/database.sqlite');

// Check if currency column exists
$result = $db->query('PRAGMA table_info(rs_properties)');
$hasCurrency = false;
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    if ($row['name'] === 'currency') {
        $hasCurrency = true;
        break;
    }
}

if ($hasCurrency) {
    echo "Currency column already exists!\n";
} else {
    // Add currency column with default USD
    $sql = "ALTER TABLE rs_properties ADD COLUMN currency VARCHAR DEFAULT 'USD'";
    if ($db->exec($sql)) {
        echo "SUCCESS: Added currency column to rs_properties table!\n";
    } else {
        echo "ERROR: Failed to add currency column - " . $db->lastErrorMsg() . "\n";
    }
}

// Update property 38 to use GBP
$sql = "UPDATE rs_properties SET currency = 'GBP' WHERE id = 38";
if ($db->exec($sql)) {
    echo "SUCCESS: Updated property 38 to use GBP!\n";
} else {
    echo "ERROR: Failed to update property - " . $db->lastErrorMsg() . "\n";
}

// Verify
$result = $db->query("SELECT id, title, price, currency FROM rs_properties WHERE id = 38");
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "\nProperty 38:\n";
    echo "  Title: " . $row['title'] . "\n";
    echo "  Price: " . $row['price'] . "\n";
    echo "  Currency: " . $row['currency'] . "\n";
}
