<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\PropertyType;

class RsPropertyTableSeeder extends Seeder
{
    protected $properties = [
        [
            'owner_id' => 3,
            'title' => 'Liberty St Apartment',
            'zipcode_id' => '11211',
            'street_name' => 'Liberty Street',
            'house_number' => '28',
            'price' => 10.52,
            'guest_count' => 8,
            'bed_count' => 6,
            'bedroom_count' => 3,
            'bathroom_count' => 2,
            'property_type' => PropertyType::SHORT_TERM_RES_APT_ROOM,
            'map_lat' => '40.7077585',
            'map_lng' => '-74.00885029999999',
            'map_address' => 'One Chase Manhattan Plaza, New York, NY 10005, EE. UU.',
        ],
        [
            'owner_id' => 4,
            'title' => 'Main St Apartment',
            'zipcode_id' => '11211',
            'street_name' => 'Main Street',
            'house_number' => '45',
            'price' => 12.52,
            'guest_count' => 5,
            'bed_count' => 3,
            'bedroom_count' => 2,
            'bathroom_count' => 1,
            'property_type' => PropertyType::SHORT_TERM_RES_APT_ROOM,
            'cancellation_type' => CancellationType::FULL,
            'cancellation_cut' => 12.52,
            'map_lat' => '40.7028919',
            'map_lng' => '-73.99054509999999',
            'map_address' => '45 Main St, Brooklyn, NY 11201, EE. UU.',
        ],
        [
            'owner_id' => 3,
            'title' => 'Liberty St House',
            'zipcode_id' => '11204',
            'street_name' => 'Liberty Street',
            'house_number' => '10',
            'price' => 20.52,
            'guest_count' => 8,
            'bed_count' => 6,
            'bedroom_count' => 3,
            'bathroom_count' => 2,
            'property_type' => PropertyType::SHORT_TERM_RES_HOU_ROOM,
            'cancellation_type' => CancellationType::PARTIAL,
            'cancellation_cut' => 5.4,
            'map_lat' => '40.7073488',
            'map_lng' => '-74.0082765',
            'map_address' => '10 Liberty St, New York, NY 10005, EE. UU.',
        ],
        [
            'owner_id' => 3,
            'title' => 'Main St House',
            'zipcode_id' => '11204',
            'street_name' => 'Main Street',
            'house_number' => '40',
            'price' => 15.52,
            'guest_count' => 5,
            'bed_count' => 3,
            'bedroom_count' => 2,
            'bathroom_count' => 1,
            'property_type' => PropertyType::SHORT_TERM_RES_HOU_ROOM,
            'additional_luxury' => 'Wi-Fi - Hot Water - Elevator - CATV',
            'additional_information' => 'This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little bit longer.',
            'map_lat' => '40.7028014',
            'map_lng' => '-73.99085409999999',
            'map_address' => '40 Main St, Brooklyn, NY 11201, EE. UU.',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->properties as $property) {
            DB::table('rs_properties')->insert($property);
        }
    }
}
