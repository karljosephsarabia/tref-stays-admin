<?php

if (!function_exists('property_image_url')) {
    /**
     * Get a properly formatted property image URL
     * Handles both local storage paths and external URLs (Vercel Blob)
     * 
     * @param string|nullurl
     * @param string $default Default placeholder image path
     * @return string
     */
    function property_image_url($url, $default = '/images/property-placeholder.jpg') {
        // If no URL provided, return placeholder
        if (empty($url)) {
            return asset($default);
        }
        
        // If it's already an external URL (Vercel Blob, etc.), return as-is
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        
        // For local storage paths, check if file exists
        $localPath = public_path($url);
        if (file_exists($localPath)) {
            return asset($url);
        }
        
        // File doesn't exist locally, return placeholder
        return asset($default);
    }
}

if (!function_exists('property_first_image')) {
    /**
     * Get the first image URL for a property
     * 
     * @param \App\SMD\Common\ReservationSystem\Models\RsProperty $property
     * @param string $default Default placeholder image path
     * @return string
     */
    function property_first_image($property, $default = '/images/property-placeholder.jpg') {
        if ($property->images && $property->images->count() > 0) {
            return property_image_url($property->images->first()->image_url, $default);
        }
        
        return asset($default);
    }
}
