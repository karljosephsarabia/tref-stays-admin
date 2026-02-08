#!/bin/bash

echo "========================================"
echo "Creating Storage Symbolic Link"
echo "========================================"
echo ""

cd "d:/Tref Website/Testing/ya last ha final ala - Copy/ivr/ivr-reservation-system-master"
php artisan storage:link

if [ $? -eq 0 ]; then
    echo ""
    echo "✓ Storage link created successfully!"
    echo ""
    echo "public/storage -> storage/app/public"
    echo ""
else
    echo ""
    echo "✗ Failed to create storage link"
    echo ""
fi

echo "========================================"
echo "Next steps:"
echo "========================================"
echo "1. Upload test image via admin panel"
echo "2. Check if it appears in storage/app/public/property_images/"
echo "3. Visit admin panel to verify images display"
echo ""
