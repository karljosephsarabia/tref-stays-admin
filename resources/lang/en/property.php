<?php

use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\PropertyType;

return [
    'show_title' => 'Manage Properties',
    'edit_property_title' => 'Edit property',
    'delete_property_title' => 'Delete property',
    'state' => 'State',
    'city' => 'City',
    'country' => 'Country',
    'area' => 'Area',
    'location' => 'Location',
    'owner' => 'Owner',
    'zipcode' => 'Zip Code',
    'street_name' => 'Street',
    'select_country' => 'Choose Country',
    'select_state' => 'Choose State',
    'select_city' => 'Choose City',
    'select_area' => 'Choose Area',
    'select_zipcode' => 'Choose Zipcode',
    'house_number' => 'House No.',
    'apt_number' => 'Apt. / Room / Suite No.',
    'cancellation_type' => 'Cancellation Type',
    'luxury' => 'Luxury',
    'information' => 'Information',
    'location_pattern' => ':city, :state',
    'cancellation' => [
        CancellationType::NONE => 'None',
        CancellationType::PARTIAL => 'Partial',
        CancellationType::FULL => 'Full',
    ],
    'type' => [
        /*PropertyType::HOUSE => 'House',
        PropertyType::APARTMENT => 'Whole Apartment',
        PropertyType::HOTEL => 'Room in a Hotel',
        PropertyType::BEDROOM => 'Private Room',
        PropertyType::SHORT_TERM_HOUSE => 'Short Term House',
        PropertyType::SHORT_TERM_APARTMENT => 'Short Term Apartment',
        PropertyType::SHORT_TERM_COMMERCIAL => 'Short Term Commercial',
        PropertyType::LONG_TERM_HOUSE => 'Long Term House',
        PropertyType::LONG_TERM_APARTMENT => 'Long Term Apartment',
        PropertyType::LONG_TERM_COMMERCIAL => 'Long Term Commercial',*/

        //phase 2.5 types
        PropertyType::SHORT_TERM_RES_APT_ROOM => 'Short Term Residential House and Apt. Room',
        PropertyType::SHORT_TERM_RES_HOU_ROOM => 'Short Term Full Hotel',
        PropertyType::SHORT_TERM_POOL => 'Swimming Pool Available By Hours',
        PropertyType::SHORT_TERM_PARKING => 'Parking Spot Available By Hours',

        PropertyType::SHORT_TERM_COM_OFFICE => 'Short Term Commercial Office',
        PropertyType::SHORT_TERM_COM_WAREHOUSE => 'Short Term Commercial Warehouse',
        PropertyType::SHORT_TERM_COM_HALL => 'Short Term Commercial Hall',

        PropertyType::LONG_TERM_RES_APARTMENT => 'Long Term Residential Apartment',
        PropertyType::LONG_TERM_RES_HOUSE => 'Long Term Residential House',
        PropertyType::LONG_TERM_PARKING => 'Long Term Parking Spot',

        PropertyType::LONG_TERM_COM_OFFICE => 'Long Term Commercial Office',
        PropertyType::LONG_TERM_COM_WAREHOUSE => 'Long Term Commercial Warehouse',

        PropertyType::SALE_RES_APARTMENT => 'Residential Apartment For Sale',
        PropertyType::SALE_RES_HOUSE => 'Residential House For Sale',

        PropertyType::SALE_COM_OFFICE => 'Commercial Office For Sale',
        PropertyType::SALE_COM_WAREHOUSE => 'Commercial Warehouse For Sale',
        PropertyType::SALE_COM_HALL => 'Commercial Hall For Sale',
        PropertyType::SALE_PARKING => 'Parking Spot For Sale',
    ],
    'option_group' => 'Reserving Option',
    'options' => [
        'CASH_SYSTEM_OWNER' => 'Pay by system, Pay by cash on arrival, connect to owner',
        'CASH_ONLY_OR_OWNER' => 'Only Cash through System or Reserve with Owner',
        'CONNECT_OWNER_ONLY' => 'Connect to Owner only',
        'CASH_ONLY' => 'System by Cash only (No Owner)',
        'SYSTEM_ONLY' => 'System only (No Owner)',
        'OWNER_OR_BROKER' => 'Owner or Broker Only',
        'CASH_SYSTEM_BROKER' => 'Pay by system, Pay by cash on arrival, connect to broker',
        'BROKER_ONLY' => 'Broker Only'
    ],
    'add_property_title' => 'Add new property',
    'bed_count' => 'Bed Count',
    'bedroom_count' => 'Bedroom Count',
    'bathroom_count' => 'Bathroom Count',
    'guest_count' => 'Guest Count',
    'cancellation_cut' => 'Cancellation Cut',
    'enter_street_name' => 'Enter Street',
    'enter_city' => 'Enter City',
    'enter_state' => 'Enter State',
    'enter_country' => 'Enter Country',
    'enter_zipcode' => 'Enter Zipcode',
    'enter_house_number' => 'Enter House No.',
    'enter_apt_number' => 'Enter Apt. / Room / Suite House No.',
    'enter_address_1' => 'e.g., street, PO Box, or company name',
    'enter_address_2' => 'e.g., apartment, suite, unit, or building',
    'address_1' => 'Address Line 1',
    'address_2' => 'Address Line 2',
    'enter_price' => 'Enter Price',
    'enter_guest_count' => 'Enter Guest Count',
    'enter_bed_count' => 'Enter Bed Count',
    'enter_bedroom_count' => 'Enter Bedroom Count',
    'enter_bathroom_count' => 'Enter Bathroom Count',
    'enter_cancellation_cut' => 'Enter Cancellation Cut',
    'error' => [
        'saving' => 'An error occurred while saving the property',
        'deleting' => 'An error occurred while deleting the property',
        'images' => 'An error occurred while processing the images',
        'availability' => 'An error occurred while processing the availability',
    ],
    'added' => 'Property successfully added',
    'edited' => 'Property successfully edited',
    'deleted' => 'Property successfully deleted',
    'confirm_delete' => 'Please confirm you want to delete this property.',
    'can_not_delete_current_property' => 'You can not delete the current property!',
    'show_on_search' => 'Show on Search',
    'images_property_title' => 'Edit Images',
    'image_add_title' => 'Add new image',
    'image' => [
        'items' => 'Images',
        'not_found' => 'No Images Found',
        'current' => 'Current Images',
        'no_thumbnail' => 'No thumbnail found',
        'delete' => 'Are you sure you want to delete this image?'
    ],
    'availability_property_title' => 'Edit Availability',
    'availability' => [
        'items' => 'This property won\'t be available on these dates',
        'not_found' => 'No records found',
        'delete' => 'Are you sure you want to delete this record?',
        'loading' => 'Loading records'
    ],
    'btn' => [
        'image' => [
            'choose' => 'Choose file',
            'save' => 'Save Thumbnail',
            'upload' => 'Upload',
            'create' => 'Create Thumbnail',
        ],
        'availability' => [
            'add' => 'Add dates',
        ]
    ]
];