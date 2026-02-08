<?php

namespace App\Http\Controllers;

use App\RsUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Enums\PropertyOption;
use SMD\Common\ReservationSystem\Enums\PropertyType;
use SMD\Common\ReservationSystem\Enums\RsLastSearchType;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsArea;
use SMD\Common\ReservationSystem\Models\RsLastSearch;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsPropertyCriterion;
use SMD\Common\ReservationSystem\Models\RsZipCode;

class SearchController extends AppBaseController
{
    protected $items;
    protected $with;

    private $property_types_map = [
        'rent_short_term' => [
            PropertyType::SHORT_TERM_RES_APT_ROOM,
            PropertyType::SHORT_TERM_RES_HOU_ROOM,
            PropertyType::SHORT_TERM_POOL,
            PropertyType::SHORT_TERM_PARKING,
            PropertyType::SHORT_TERM_COM_OFFICE,
            PropertyType::SHORT_TERM_COM_WAREHOUSE,
            PropertyType::SHORT_TERM_COM_HALL,
        ],
        'rent_long_term' => [
            PropertyType::LONG_TERM_RES_APARTMENT,
            PropertyType::LONG_TERM_RES_HOUSE,
            PropertyType::LONG_TERM_PARKING,
            PropertyType::LONG_TERM_COM_OFFICE,
            PropertyType::LONG_TERM_COM_WAREHOUSE,
        ],
        'sale' => [
            PropertyType::SALE_RES_APARTMENT,
            PropertyType::SALE_RES_HOUSE,
            PropertyType::SALE_COM_OFFICE,
            PropertyType::SALE_COM_WAREHOUSE,
            PropertyType::SALE_COM_HALL,
            PropertyType::SALE_PARKING,
        ],

        'rent_short_term_room' => [PropertyType::SHORT_TERM_RES_APT_ROOM],
        'rent_short_term_hotel' => [PropertyType::SHORT_TERM_RES_HOU_ROOM],
        'rent_short_term_hall' => [PropertyType::SHORT_TERM_COM_HALL],
        'rent_short_term_parking' => [PropertyType::SHORT_TERM_PARKING],
        'rent_short_term_pool' => [PropertyType::SHORT_TERM_POOL],

        'rent_long_term_residential' => [
            PropertyType::LONG_TERM_RES_APARTMENT,
            PropertyType::LONG_TERM_RES_HOUSE,
            PropertyType::LONG_TERM_PARKING,
        ],
        'rent_long_term_commercial' => [
            PropertyType::LONG_TERM_COM_OFFICE,
            PropertyType::LONG_TERM_COM_WAREHOUSE,
        ],

        'sale_residential' => [
            PropertyType::SALE_RES_APARTMENT,
            PropertyType::SALE_RES_HOUSE,
        ],
        'sale_commercial' => [
            PropertyType::SALE_COM_OFFICE,
            PropertyType::SALE_COM_WAREHOUSE,
            PropertyType::SALE_COM_HALL,
            PropertyType::SALE_PARKING,
        ],

        'rent_long_term_residential_apartment' => [PropertyType::LONG_TERM_RES_APARTMENT,],
        'rent_long_term_residential_house' => [PropertyType::LONG_TERM_RES_HOUSE,],
        'rent_long_term_residential_parking' => [PropertyType::LONG_TERM_PARKING,],

        'rent_long_term_commercial_office' => [PropertyType::LONG_TERM_COM_OFFICE,],
        'rent_long_term_commercial_warehouse' => [PropertyType::LONG_TERM_COM_WAREHOUSE,],

        'sale_residential_apartment' => [PropertyType::SALE_RES_APARTMENT,],
        'sale_residential_house' => [PropertyType::SALE_RES_HOUSE,],

        'sale_commercial_office' => [PropertyType::SALE_COM_OFFICE,],
        'sale_commercial_warehouse' => [PropertyType::SALE_COM_WAREHOUSE,],
        'sale_commercial_hall' => [PropertyType::SALE_COM_HALL,],
        'sale_commercial_parking' => [PropertyType::SALE_PARKING],
    ];

    public function __construct(Request $request)
    {
        $this->middleware('ajax.json')->only(['rooms']);
        $this->middleware('auth')->only('history');

        $this->items = [
            ['name' => trans('general.search'), 'route' => route('search')]
        ];

        $this->with['view'] = $request->has('view') ? $request->input('view') : 'grid';
    }

    public function room(Request $request, $id)
    {
        try {
            $user = $request->user();

            /** @var RsProperty $property */
            $property = $this->properties($request->user())->active()->findOrFail($id);

            if(!is_null_or_empty($user)){
                $search_history = $this->searches_history($user, $id)->first();

                if ($search_history == null) {
                    $search_history = new RsLastSearch();
                    $search_history->search_value = $id;
                    $search_history->property_type = $property->property_type;
                    $search_history->search_type = RsLastSearchType::PROPERTY;
                    $search_history->for_ivr = 0;
                }

                $search_history->rs_user_id = $user->id;
                $search_history->client_ip = $request->ip();
                $search_history->save();
            }

            //get property options
            $options = null;

            switch ($property->property_option_group) {
                case 'CASH_SYSTEM_OWNER':
                    $options = PropertyOption::CASH_SYSTEM_OWNER;
                    break;
                case 'CASH_ONLY_OR_OWNER':
                    $options = PropertyOption::CASH_ONLY_OR_OWNER;
                    break;
                case 'CASH_ONLY':
                    $options = PropertyOption::CASH_ONLY;
                    break;
                case 'SYSTEM_ONLY':
                    $options = PropertyOption::SYSTEM_ONLY;
                    break;
                case 'CONNECT_OWNER_ONLY':
                    $options = PropertyOption::CONNECT_OWNER_ONLY;
                    break;
                case 'OWNER_OR_BROKER':
                    $options = PropertyOption::OWNER_OR_BROKER;
                    break;
                case 'BROKER_ONLY':
                    $options = PropertyOption::BROKER_ONLY;
                    break;
            }

            $name = (GeneralHelper::isNullOrEmpty($property->title) ? $property->id : $property->title);

            $this->with['property'] = $property;
            $this->with['items'] = array_merge($this->items, [
                ['name' => trans('general.rooms'), 'route' => route('search', request()->all())],
                ['name' => $name, 'class' => 'active']
            ]);
            $this->with['title'] = $name;
            $this->with['property_options'] = $options;

            return view('search')->with($this->with);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        }
    }

    public function search(Request $request)
    {
        $this->with['title'] = trans('general.rooms');
        $this->with ['items'] = array_merge($this->items, [
            ['name' => trans('general.rooms'), 'class' => 'active']
        ]);

        /*$this->with['propTypes1'] = [
            'rent_short_term' => 'Short Term Rent',
            'rent_long_term' => 'Long Term Rent',
            'sale' => 'Sale'
        ];

        $this->with['propTypes2'] = [
            'rent_short_term' => [
                'room' => 'House and Apt. Rooms',
                'hotel' => 'Full Hotel after the season',
                'hall' => 'Simche Hall',
                'parking' => 'Parking',
                'pool' => 'Swimming Pools'
            ],
            'rent_long_term' => [
                'residential' => 'Residential',
                'commercial' => 'Commercial',
            ],
            'sale' => [
                'residential' => 'Residential',
                'commercial' => 'Commercial',
            ]
        ];

        $this->with['propTypes3'] = [
            'rent_long_term.residential' => [
                'apartment' => 'Apartment',
                'house' => 'House',
                'parking' => 'Parking',
            ],
            'rent_long_term.commercial' => [
                'office' => 'Office',
                'warehouse' => 'Warehouse'
            ],
            'sale.residential' => [
                'apartment' => 'Apartment',
                'house' => 'House',
            ],
            'sale.commercial' => [
                'office' => 'Office',
                'warehouse' => 'Warehouse',
                'hall' => 'Hall',
                'parking' => 'Parking'
            ]
        ];
        */

        return view('search')->with($this->with);
    }

    public function rooms(Request $request)
    {
        try {
            $result = [];

            $start_date = $request->input('check_in');
            $end_date = $request->input('check_out');

            if (GeneralHelper::isNullOrEmpty($start_date)) {
                $today = new \DateTime('today');
                $start_date = $today->format('Y-m-d');
            }

            if (GeneralHelper::isNullOrEmpty($end_date)) {
                $tomorrow = new \DateTime('tomorrow');
                $end_date = $tomorrow->format('Y-m-d');
            }

            $properties = $this->properties($request->user())
                ->activeAndAvailable($start_date, $end_date)
                ->with(['images' => function ($image) {
                    $image->showOnSearch();
                }]);

            if (!GeneralHelper::isNullOrEmpty($request->input('guest_count'))) {
                $properties = $properties->where('guest_count', '>=', $request->input('guest_count'));
            }

            if (!GeneralHelper::isNullOrEmpty($request->input('bed_count'))) {
                $properties = $properties->where('bed_count', '>=', $request->input('bed_count'));
            }

            //========================================================================
            //========================================================================
            //========================================================================
            if (!GeneralHelper::isNullOrEmpty($request->input('type'))) {

                $mapTypeIndex = '';

                if(!GeneralHelper::isNullOrEmpty($request->input('type'))) {
                    $mapTypeIndex .= $request->input('type');
                }

                if(!GeneralHelper::isNullOrEmpty($request->input('type_2'))) {
                    $mapTypeIndex .= '_' . $request->input('type_2');
                }

                if(!GeneralHelper::isNullOrEmpty($request->input('type_3'))) {
                    $mapTypeIndex .= '_' . $request->input('type_3');
                }

                if($request->input('type_2') == 'hall' || $request->input('type_3') == 'hall') {
                    $properties = $properties->whereIn('property_type', $this->property_types_map[$mapTypeIndex])
                        ->orWhereIn('property_type', [PropertyType::SHORT_TERM_COM_HALL, PropertyType::SALE_COM_HALL])
                    ->orWhereIn('second_property_type',  [PropertyType::SHORT_TERM_COM_HALL, PropertyType::SALE_COM_HALL]);
                } else {
                    //$properties = $properties->where('property_type', $request->input('type'));
                    $properties = $properties->whereIn('property_type', $this->property_types_map[$mapTypeIndex]);
                }
            }
            //========================================================================
            //========================================================================
            //========================================================================
            //square feet
            if (!GeneralHelper::isNullOrEmpty($request->input('type'))) {
                if ($request->input('type') != 'rent_short_term') {
                    if (!GeneralHelper::isNullOrEmpty($request->input('square_feet_from'))) {
                        $properties = $properties->where('square_feet', '>=', $request->input('square_feet_from'));
                    }

                    if (!GeneralHelper::isNullOrEmpty($request->input('square_feet_to'))) {
                        $properties = $properties->where('square_feet', '<=', $request->input('square_feet_to'));
                    }

                    if ($request->input('type') == 'rent_long_term') {
                        //leasing years
                        if (!GeneralHelper::isNullOrEmpty($request->input('lease_year_from'))) {
                            $properties = $properties->where('years_lease', '>=', $request->input('lease_year_from'));
                        }

                        if (!GeneralHelper::isNullOrEmpty($request->input('lease_year_to'))) {
                            $properties = $properties->where('years_lease', '<=', $request->input('lease_year_to'));
                        }
                    }
                }
            }
            //========================================================================
            //========================================================================
            //========================================================================
            //date posted
            if (!GeneralHelper::isNullOrEmpty($request->input('type'))) {
                if ($request->input('type') == 'sale') {
                    if (!GeneralHelper::isNullOrEmpty($request->input('date_posted'))) {
                        switch ($request->input('date_posted')) {
                            case 'all_dates':
                                break;
                            case 'within_24_hours':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subDay());
                                break;
                            case 'within_3_days':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subDays(3));
                                break;
                            case 'within_last_week':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subWeek());
                                break;
                            case 'within_last_2_weeks':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subWeeks(2));
                                break;
                            case 'within_last_month':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subMonth());
                                break;
                            case 'within_last_3_months':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subMonths(3));
                                break;
                            case 'within_last_year':
                                $properties = $properties->where('rs_properties.created_at', '>=', Carbon::now()->subYear());
                                break;
                        }
                    }
                }
            }
            //========================================================================
            //========================================================================
            //========================================================================
            if (!GeneralHelper::isNullOrEmpty($request->input('zipcode'))) {
                //$properties = $properties->where('zipcode_id', $request->input('zipcode'));
                $zipcodes[] = $request->input('zipcode');
                $zipcode = RsZipCode::where('zipcode', $request->input('zipcode'))->first();

                if($zipcode){
                    $area = RsArea::with(['zipcodes'])->where('id', $zipcode->area_id)->first();

                    if($area){
                        foreach ($area->zipcodes as $zcode) {
                            $zipcodes[] = $zcode->zipcode;
                        }
                    }
                }

                $properties = $properties->whereIn('zipcode_id', $zipcodes);
            }

            $amenities = (array)$request->input('amenities', []);

            if (count($amenities) > 0) {
                $propertyIds = RsPropertyCriterion::whereIn('criterion_id', $amenities)
                    ->select('property_id')->distinct()->get()
                    ->map(function ($item) {
                        return $item->property_id;
                    })->toArray();
                if (count($propertyIds) > 0) {
                    $properties = $properties->whereIn('rs_properties.id', $propertyIds);
                }
            }

            $properties = $properties->get();

            foreach ($properties as $property) {

                $images = [];

                foreach ($property->images as $image) {
                    $images[] = GeneralHelper::isNullOrEmpty($image->thumbnail_url) ? $image->url : $image->thumbnail_url;
                }

                if (count($images) == 0) {
                    $images[] = '/images/properties/images_not_found.png';
                }

                $result[] = [
                    'id' => $property->id,
                    'map_lat' => $property->map_lat,
                    'map_lng' => $property->map_lng,
                    'map_address' => map_property_location($property),
                    'title' => $property->title,
                    'price' => $property->price,
                    'luxury' => $property->additional_luxury,
                    'specs' => property_specs($property),
                    'images' => $images,
                    'type_location' => property_type_location($property),
                    'map_image' => google_static_map_url($property, 17),
                    'route' => route(($request->user() == null ? 'room_no_auth' : 'room'), ['id' => $property->id])
                ];
            }

            return $this->jsonSuccessResponse(['data' => $result]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $history = $this->searches_history($user)
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($item) {
                $item->property = RsProperty::whereId($item->search_value)->first();
                $item->date = date_formatter($item->updated_at);
                /*$item->user = RsUser::whereRaw('(id = ? or phone_number = ?)', [
                    $item->rs_user_id,
                    $item->phone_number
                ])->first();*/
                return $item;
            })
            ->groupBy('date');

        $this->with['history'] = $history;
        $this->with['title'] = __('general.search_history');
        $this->with['items'] = array_merge($this->items, [
            ['name' => __('general.history'), 'class' => 'active']
        ]);

        return view('search-history')->with($this->with);
    }

    //====================================
    //LINK
    //====================================
    public function embeddedLink(Request $request, $id = null)
    {
        $property = RsProperty::find($id);

        $images = [];

        foreach ($property->images as $image) {
            $images[] = GeneralHelper::isNullOrEmpty($image->thumbnail_url) ? $image->url : $image->thumbnail_url;
        }

        if (count($images) == 0) {
            $images[] = '/images/properties/images_not_found.png';
        }

        $result = [
            'id' => $property->id,
            'map_lat' => $property->map_lat,
            'map_lng' => $property->map_lng,
            'map_address' => map_property_location($property),
            'title' => $property->title,
            'price' => $property->price,
            'luxury' => $property->additional_luxury,
            'specs' => property_specs($property),
            'images' => $images,
            'type_location' => property_type_location($property),
            'map_image' => google_static_map_url($property, 17),
            'route' => route(($request->user() == null ? 'room_no_auth' : 'room'), ['id' => $property->id])
        ];

        return view('property-embed')->with(['property' => $result]);
    }
    //====================================
    /**
     * @param RsUser $user
     * @param int|string|null $property_id
     * @return \Illuminate\Database\Eloquent\Builder|RsLastSearch
     */
    private function searches_history($user, $property_id = null)
    {
        $history = RsLastSearch::whereSearchType(RsLastSearchType::PROPERTY)
            ->whereRaw('(rs_user_id = ? or cid = ?)', [
                $user->id,
                prepare_phone_number($user->phone_number)
            ]);

        if (!is_null_or_empty($property_id)) {
            $history = $history->where('search_value', $property_id);
        }

        return $history;
    }

    private function properties($user)
    {
        /*return $user != null && $user->is_owner
            ? RsProperty::where('owner_id', $user->id)
            : RsProperty::query();*/

        return RsProperty::query();
    }
}