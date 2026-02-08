<?php
/**
 * Created by PhpStorm.
 * User: jerdg
 * Date: 6/23/2017
 * Time: 10:29 AM
 */

use SMD\Common\ReservationSystem\Models\RsIncomingReport;
use SMD\Common\ReservationSystem\Models\RsProperty;
use App\RsUser;
use SMD\Common\ReservationSystem\Models\RsZipCode;

if (!function_exists('currency_symbol')) {
    /**
     * Get currency symbol from currency code.
     * @param string $currency
     * @return string
     */
    function currency_symbol($currency = 'USD')
    {
        $symbols = [
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'CAD' => 'CA$',
            'ILS' => '₪',
        ];
        return $symbols[$currency] ?? '$';
    }
}

if (!function_exists('format_price')) {
    /**
     * Format price with correct currency symbol.
     * @param float $price
     * @param string $currency
     * @return string
     */
    function format_price($price, $currency = 'USD')
    {
        return currency_symbol($currency) . number_format($price, 0);
    }
}

if (!function_exists('property_specs')) {
    /**
     * Generate property info details.
     * @param RsProperty $property
     * @param string $separator
     * @return string
     */
    function property_specs($property, $separator = ' <span aria-hidden="true" style="vertical-align: super;">.</span> ')
    {
        $pieces = [
            str_replace([':guests'], [$property->guest_count], trans('reservation.details.guests')),
            str_replace([':bedrooms'], [$property->bedroom_count], trans('reservation.details.bedrooms')),
            str_replace([':beds'], [$property->bed_count], trans('reservation.details.beds')),
            str_replace([':baths'], [$property->bathroom_count], trans('reservation.details.baths')),
        ];

        return join($separator, $pieces);
    }
}

if (!function_exists('create_incoming_record')) {
    /**
     * @param RsUser $user
     * @param string $start_date
     * @param string $end_date
     * @return RsIncomingReport
     */
    function create_incoming_record($user, $start_date, $end_date)
    {
        $incoming = $user->incoming($start_date, $end_date);

        $income = new RsIncomingReport();

        $income->user_id = $user->id;

        $income->starting_at = $start_date;
        $income->ending_at = $end_date;

        $income->broker_fee_total = $incoming->broker_fee_total;
        $income->cancellation_fee_total = $incoming->cancellation_fee_total;
        $income->payment_total = $incoming->payment_total;
        $income->refund_total = $incoming->refund_total;
        $income->reservation_total = $incoming->reservation_total;
        $income->commission_total = $incoming->commission_total;

        $last_invoice = $user->incomingReports()->orderByDesc('id')->first();
        $income->starting_balance = $last_invoice == null ? 0 : $last_invoice->ending_balance;

        $income->ending_balance = $user->balance;

        return $income;
    }
}

if (!function_exists('report_date_range')) {

    /**
     * @param string $start
     * @param string $end
     * @return string
     * @throws Exception
     */
    function report_date_range($start, $end)
    {
        return \App\Helpers\GeneralHelper::ReportDateRange($start, $end);
    }
}

if (!function_exists('user_full_name')) {
    /**
     * Get full name formatted to user
     * @param RsUser $user
     * @return string
     */
    function user_full_name($user)
    {
        return \SMD\Common\ReservationSystem\Helpers\GeneralHelper::getUserFullName($user, trans('profile.format.full_name'), trans('general.n_a'));
    }
}

if (!function_exists('middleware_role')) {
    /**
     * Get middleware roles for routes
     * @param array $roles
     * @param string $prefix
     * @return string
     */
    function middleware_role($roles, $prefix = 'role:')
    {
        return $prefix . join(',', $roles);
    }
}

if (!function_exists('user_address_1')) {
    /**
     * Get address 2 formatted to user
     * @param RsUser $user
     * @return string
     */
    function user_address_1($user)
    {
        return isset($user) && $user && ($user->address_1 || $user->address_2)
            ? str_replace([':address_1', ':address_2'], [$user->address_1, $user->address_2], trans('profile.format.address_1'))
            : trans('general.n_a');
    }
}

if (!function_exists('user_address_2')) {
    /**
     * Get address 2 formatted to user
     * @param RsUser $user
     * @return string
     */
    function user_address_2($user)
    {
        return isset($user) && $user && ($user->city || $user->state || $user->zipcode)
            ? str_replace([':city', ':state_code', ':zipcode'], [$user->city, $user->state, $user->zipcode], trans('profile.format.address_2'))
            : trans('general.n_a');
    }
}

if (!function_exists('array_trans')) {
    /**
     * Generate trans array from array.
     * @param $array
     * @param $prefix
     * @return array
     */
    function array_trans($array, $prefix)
    {
        $result = [];
        foreach ($array as $item) {
            $result[$item] = trans($prefix . $item);
        }
        return $result;
    }
}

if (!function_exists('cancellation_policy')) {

    /**
     * @param RsProperty $property
     * @param string $new_line
     * @return string
     */
    function cancellation_policy($property)
    {
        $policy = trans('reservation.policies.' . $property->cancellation_type);

        $cancellation = trans('reservation.cancellation.' . $property->cancellation_type);
        $cancellation = '<strong>' . str_replace([':amount'], [$property->cancellation_cut], $cancellation) . '</strong>';

        return str_replace([':cancellation_type'], [$cancellation], $policy);
    }
}

if (!function_exists('property_type_location')) {
    /**
     * @param RsProperty $property
     * @return string
     */
    function property_type_location($property)
    {
        $type = trans('property.type.' . $property->property_type);
        $zipcode = RsZipCode::whereZipcode($property->zipcode_id)->first()->toArray();
        return str_replace([':type', ':place'], [$type, $zipcode['city']], trans('reservation.type_place'));
    }
}

if (!function_exists('google_embed_map_url')) {
    /**
     * @param RsProperty $property
     * @return string
     */
    function google_embed_map_url($property)
    {
        $base_url = 'https://www.google.com/maps/embed/v1/place?q=:place&key=:key&language=:lang';
        $values = [map_property_location($property), env('GOOGLE_API_KEY'), app()->getLocale()];

        return str_replace([':place', ':key', ':lang'], $values, $base_url);
    }
}

if (!function_exists('google_static_map_url')) {
    /**
     * @param RsProperty $property
     * @param int $zoom
     * @param string $size
     * @return string
     */
    function google_static_map_url($property, $zoom = 17, $size = '382x220')
    {
        $base_url = 'https://maps.googleapis.com/maps/api/staticmap?markers=:markers&zoom=:zoom&size=:size&key=:key&language=:lang';
        $values = [map_property_location($property), $zoom, $size, env('GOOGLE_API_KEY'), app()->getLocale()];

        return str_replace([':markers', ':zoom', ':size', ':key', ':lang'], $values, $base_url);
    }
}

if (!function_exists('google_script_api_url')) {

    /**
     * @param string $callback
     * @return string
     */
    function google_script_api_url($callback = null)
    {
        $base_url = 'https://maps.googleapis.com/maps/api/js?:key:callback&libraries=places&language=:lang';

        $base_url = str_replace([':callback'], [is_null_or_empty($callback) ? '' : '&callback=' . $callback], $base_url);

        $base_url = str_replace([':key', ':lang'], ['key=' . env('GOOGLE_API_KEY'), app()->getLocale()], $base_url);
        //$base_url = str_replace([':key'], ['v=3.exp'], $base_url);

        return $base_url;
    }
}

if (!function_exists('base64ToImage')) {

    /**
     * @param string $base64_string
     * @param string $file_name
     * @return string
     */
    function base64ToImage($base64_string, $file_name)
    {
        $data = explode(',', $base64_string);

        $file_name .= '.' . str_replace(['data:image/', ';base64'], ['', ''], $data[0]);

        $file = fopen(public_path($file_name), "wb");

        fwrite($file, base64_decode($data[1]));
        fclose($file);

        return $file_name;
    }
}

if (!function_exists('map_property_location')) {
    /**
     * @param RsProperty $property
     * @return string
     */
    function map_property_location($property)
    {
        if (is_null_or_empty($property->map_address)) {
            $values = [$property->house_number, $property->street_name, $property->zipcode_id];

            return str_replace([':house', ':street', ':zipcode'], $values, ':house :street :zipcode');
        }

        return $property->map_address;
    }
}

if (!function_exists('property_address')) {
    /**
     * @param RsProperty $property
     * @return string
     */
    function property_address($property)
    {
        $zipcode = RsZipCode::whereZipcode($property->zipcode_id)->first()->toArray();

        $pieces = [
            str_replace([':number', ':street'], [$property->house_number, $property->street_name], ':number :street'),
            $zipcode['city'],
            str_replace([':number', ':street'], [$zipcode['state'], $property->zipcode_id], ':number :street'),
            $zipcode['country']
        ];

        return join(', ', $pieces);
    }
}

if (!function_exists('property_location')) {
    /**
     * @param RsProperty $property
     * @return string|null
     */
    function property_location($property)
    {
        $zipcode = RsZipCode::whereZipcode($property->zipcode_id)->first();

        if ($zipcode == null) return $property->zipcode_id;

        $values = [$zipcode->city ?: "", $zipcode->state ?: ""];

        return str_replace([':city', ':state'], $values, trans('property.location_pattern'));
    }
}

if (!function_exists('prepare_phone_number')) {
    /**
     * @param string $phone_number
     * @return string|null
     */
    function prepare_phone_number($phone_number)
    {
        return preg_replace('/^(011|\+?1)/', '', $phone_number);
    }
}

if (!function_exists('is_null_or_empty')) {

    /**
     * Check if variable is null or empty
     * @param $variable
     * @return bool
     */
    function is_null_or_empty($variable)
    {
        return \SMD\Common\ReservationSystem\Helpers\GeneralHelper::isNullOrEmpty($variable);
    }
}

if (!function_exists('format_phone_number')) {

    /**
     * Format phone number for presentation.
     * I.e:
     * 5555555555: 555-555-5555
     * 15555555555: 1-555-555-5555
     * 011550987654: 011-550987654
     * @param $number
     * @return string
     */
    function format_phone_number($number)
    {
        if (is_null_or_empty($number)) return $number;

        if (str_starts_with($number, '011')) {
            return substr($number, 0, 3) . '-' . substr($number, 3);
        }

        $number = substr($number, -10);
        return substr($number, 0, 3) . '-' . substr($number, 3, 3) . '-' . substr($number, 6);
    }

}

if (!function_exists('object_json')) {
    function object_json($value, $array = false)
    {
        $result = [];
        foreach ($value as $key => $item) {
            if (!$array && is_array($item)) continue;
            $result[$key] = $item;
        }
        return json_encode((object)$result);
    }
}

if (!function_exists('date_formatter')) {

    /**
     * @param string $date
     * @param string $format
     * @return string
     * @throws Exception
     */
    function date_formatter($date, $format = 'm/d/Y')
    {
        return \App\Helpers\GeneralHelper::DateFormatter($date, $format);
    }
}

if (!function_exists('sem_get')) {

    function sem_get($key)
    {
        if (!is_dir(Storage::path('semaphore'))) {
            Storage::makeDirectory('semaphore');
        }
        return fopen(Storage::path('semaphore/_sem.' . $key), 'w+');
    }

    function sem_acquire($sem_id)
    {
        return flock($sem_id, LOCK_EX);
    }

    function sem_release($sem_id)
    {
        return flock($sem_id, LOCK_UN);
    }

    function sem_remove($sem_id)
    {
        return true;
    }

}

if (!function_exists('property_image_url')) {
    /**
     * Get a properly formatted property image URL
     * Handles external URLs (Vercel Blob, Render Storage), Node.js uploads, Laravel storage, and missing images
     * 
     * @param string|null $url
     * @param string $default Default placeholder image path
     * @return string
     */
    function property_image_url($url, $default = '/images/property-placeholder.svg') {
        // If no URL provided, return placeholder
        if (empty($url)) {
            return asset($default);
        }
        
        // If it's already a full external URL (Vercel Blob, Render Storage, etc.), return as-is
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        
        // Handle Node.js backend uploads (/uploads/ paths)
        // These are from the tref-stays-design Node.js server on port 3000
        if (str_starts_with($url, '/uploads/')) {
            // Check if we have a backend API URL configured
            $apiUrl = env('BACKEND_API_URL', 'http://localhost:3000');
            return rtrim($apiUrl, '/') . $url;
        }
        
        // Check if we have a RENDER_STORAGE_URL configured for Render-hosted storage
        $renderStorageUrl = env('RENDER_STORAGE_URL');
        if ($renderStorageUrl && str_starts_with($url, '/storage/')) {
            // Convert /storage/path to Render storage URL
            $path = str_replace('/storage/', '', $url);
            return rtrim($renderStorageUrl, '/') . '/' . ltrim($path, '/');
        }
        
        // For local storage paths, check if file exists
        if (str_starts_with($url, '/storage/')) {
            $localPath = public_path($url);
            
            // If file exists locally, return asset URL
            if (file_exists($localPath)) {
                return asset($url);
            }
            
            // File doesn't exist locally - might be on Render
            // Try to return the path anyway, browser will handle 404
            return asset($url);
        }
        
        // For other paths (not starting with /storage/), try as regular asset
        $localPath = public_path($url);
        if (file_exists($localPath)) {
            return asset($url);
        }
        
        // File doesn't exist, return placeholder
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
    function property_first_image($property, $default = '/images/property-placeholder.svg') {
        if ($property->images && $property->images->count() > 0) {
            return property_image_url($property->images->first()->image_url, $default);
        }
        
        return asset($default);
    }
}