<?php

return [

    /**
     * Client configuration
     */
    'client' => [
        'auth' => [
            'sms_twilio_sid' => env('SMS_TWILIO_SID'),
            'sms_twilio_token' => env('SMS_TWILIO_TOKEN'),
        ],
        'fetch' => [
            'country_code' => null,
            'type' => null,
            'add_ons' => 'whitepages_pro_caller_id',
            'right_party_contacted_date' => null,
        ],
        'sms_twilio_from' => env('SMS_TWILIO_FROM'),
    ],

    /**
     * Whitepages response matching
     */
    'whitepages_response_match' => [
        'request_sid' => 'request_sid',
        'id' => 'result.id',
        'phone_number' => 'result.phone_number',
        'is_valid' => 'result.is_valid',
        'country_calling_code' => 'result.country_calling_code',
        'line_type' => 'result.line_type',
        'carrier' => 'result.carrier',
        'is_prepaid' => 'result.is_prepaid',
        'is_commercial' => 'result.is_commercial',
        'name' => ['result.belongs_to.name',
            'result.belongs_to.0.name'],
        'first_name' => ['result.belongs_to.firstname',
            'result.belongs_to.0.firstname'],
        'middle_name' => ['result.belongs_to.middlename',
            'result.belongs_to.0.middlename'],
        'last_name' => ['result.belongs_to.lastname',
            'result.belongs_to.0.lastname'],
        'age_range' => ['result.belongs_to.age_range',
            'result.belongs_to.0.age_range'],
        'gender' => ['result.belongs_to.gender',
            'result.belongs_to.0.gender'],
        'type' => ['result.belongs_to.type',
            'result.belongs_to.0.type'],
        'link_to_phone_start_date' => ['result.belongs_to.link_to_phone_start_date',
            'result.belongs_to.0.link_to_phone_start_date'],
        'industry' => ['result.belongs_to.industry',
            'result.belongs_to.0.industry'],
        'location_type' => 'result.current_addresses.0.location_type',
        'address_id' => 'result.current_addresses.0.address_id',
        'street_line_1' => 'result.current_addresses.0.street_line_1',
        'street_line_2' => 'result.current_addresses.0.street_line_2',
        'city' => 'result.current_addresses.0.city',
        'postal_code' => 'result.current_addresses.0.postal_code',
        'zip4' => 'result.current_addresses.0.zip4',
        'state_code' => 'result.current_addresses.0.state_code',
        'country_code' => 'result.current_addresses.0.country_code',
        'latitude' => 'result.current_addresses.0.lat_long.latitude',
        'longitude' => 'result.current_addresses.0.lat_long.longitude',
        'link_to_person_start_date' => 'result.current_addresses.0.link_to_person_start_date',
    ],

    'whitepages_response_dates' => [
        'link_to_person_start_date',
        'link_to_phone_start_date',
    ],

];
