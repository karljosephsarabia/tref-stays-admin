<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Checking rs_property_images table...\n\n";

try {
    // Get sample image URLs from database
    $images = DB::table('rs_property_images')
        ->where('active', true)
        ->limit(10)
        ->get(['id', 'property_id', 'image_url', 'active']);
    
    echo "📊 Found " . $images->count() . " images:\n\n";
    
    foreach ($images as $image) {
        echo "Image ID: {$image->id}\n";
        echo "Property ID: {$image->property_id}\n";
        echo "URL: {$image->image_url}\n";
        echo "Active: " . ($image->active ? 'Yes' : 'No') . "\n";
        
        // Check if URL is local or external
        if (strpos($image->image_url, 'http://') === 0 || strpos($image->image_url, 'https://') === 0) {
            echo "Type: 🌐 External URL\n";
        } else {
            echo "Type: 📁 Local path\n";
            // Check if file exists locally
            $localPath = public_path($image->image_url);
            if (file_exists($localPath)) {
                echo "Status: ✅ File exists\n";
            } else {
                echo "Status: ❌ File NOT found at: {$localPath}\n";
            }
        }
        echo str_repeat('-', 80) . "\n";
    }
    
    // Get properties with no images
    $propertiesWithoutImages = DB::table('rs_properties')
        ->leftJoin('rs_property_images', function($join) {
            $join->on('rs_properties.id', '=', 'rs_property_images.property_id')
                 ->where('rs_property_images.active', '=', true);
        })
        ->whereNull('rs_property_images.id')
        ->where('rs_properties.active', true)
        ->count();
    
    echo "\n📝 Summary:\n";
    echo "Properties without images: {$propertiesWithoutImages}\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
