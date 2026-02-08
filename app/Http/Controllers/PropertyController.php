<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\PropertyOption;
use SMD\Common\ReservationSystem\Enums\PropertyType;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Helpers\Holidays;
use SMD\Common\ReservationSystem\Models\RsArea;
use SMD\Common\ReservationSystem\Models\RsCreditCard;
use SMD\Common\ReservationSystem\Models\RsCriterionType;
use SMD\Common\ReservationSystem\Models\RsHoliday;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsPropertyAvailability;
use SMD\Common\ReservationSystem\Models\RsPropertyCriterion;
use SMD\Common\ReservationSystem\Models\RsPropertyImage;
use App\RsUser;
use SMD\Common\ReservationSystem\Models\RsPropertyOption;
use SMD\Common\ReservationSystem\Models\RsPropertyPackage;
use SMD\Common\ReservationSystem\Models\RsPropertyRestriction;
use SMD\Common\ReservationSystem\Models\RsSearchCriterion;
use SMD\Common\ReservationSystem\Models\RsStreetName;
use SMD\Common\ReservationSystem\Models\RsZipCode;
use SMD\Common\Traits\StripeTrait;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\AddEditProperty;

class PropertyController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only([
            'add', 'edit', 'delete', 'images', 'availabilities', 'mapLocation'
        ]);
    }

    public function show(Request $request, $id = null)
    {
        //load search criteria types
        $criteriaTypes = RsCriterionType::where('id', '>', 0)
            ->get(['id', 'name'])->toArray();
        //print_r($criteriaTypes); die;

        $with = [
            'cancellation_types' => CancellationType::TYPES,
            'property_types' => array_slice(PropertyType::TYPES, 0),
            'criteria_types' => $criteriaTypes,
            'property_options' => PropertyOption::OPTION_GROUPS,
        ];

        $user = $request->user();

        if ($user->is_broker) {
            //$with['owners'] = RsUser::owners()->get(); //changed now that every user can be owner
            $with['owners'] = RsUser::active()->get();
        }

        if (is_null($id)) {
            return view('properties')->with($with);
        } else {
            try {
                $with['edit_property'] = $this->properties($user)->findOrFail($id);
                return view('properties')->with($with);
            } catch (ModelNotFoundException $e) {
                return $this->notFoundResponse();
            } catch (\Exception $e) {
                return $this->errorProcessingResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
            }
        }
    }

    private function propertyDirectLink($id, $retUrl = false)
    {
        $url = route('search_embedded_link', ['id' => $id]);

        if($retUrl){
            return $url;
        }

        $htmlCode = '<div style="width:460px; height:460px; position:relative; padding:0;">
    <iframe src="' . $url . '" style="position:absolute; top:0px;
        left:0px; width:100%; height:100%; border: none; overflow: hidden;">  
    </iframe>
</div>';

        return $htmlCode;
    }

    public function add(AddEditProperty $request)
    {
        try {
            $property = new RsProperty();
            $property->owner_id = $request->input('owner_id');
            $property->phone_number = $request->input('phone_number');
            $property->title = $request->input('title');
            $property->property_type = $request->input('property_type');
            $property->zipcode_id = $request->input('zipcode_id');
            $property->street_name = $request->input('street_name');
            $property->house_number = $request->input('house_number');
            $property->apt_number = $request->input('apt_number');
            $property->price = $request->input('price');
            $property->guest_count = $request->input('guest_count');
            $property->bed_count = $request->input('bed_count');
            $property->bedroom_count = $request->input('bedroom_count');
            $property->bathroom_count = $request->input('bathroom_count');

            $property->when_is_available = $request->input('when_is_available');
            $property->how_long_is_available = 100000; //$request->input('how_long_is_available');

            if(!is_null_or_empty($property->how_long_is_available)) {
                $property->how_long_start = Carbon::now()->startOfDay();
                $property->how_long_end = Carbon::now()->addMonths($property->how_long_is_available);
            }

            $property->cancellation_type = $request->input('cancellation_type');
            $property->cancellation_cut = $request->input('cancellation_cut');
            $property->additional_luxury = $request->input('additional_luxury');
            $property->additional_information = $request->input('additional_information');
            $property->map_lat = $request->input('map_lat');
            $property->map_lng = $request->input('map_lng');
            $property->map_address = $request->input('map_address');
            $property->billing_mode = $request->input('billing_mode');

            $property->active = $request->has('active');

            $property->couple_quantity = $request->input('couple_quantity');
            $property->floor_location = $request->input('floor_location');

            if($request->has('suitable_hall')) {
                $property->second_property_type = PropertyType::SHORT_TERM_COM_HALL;
                $property->hall_price = $request->input('hall_price');
            }

            $property->has_elevator = $request->has('has_elevator');

            $property->save();
            $property->tts_text = GeneralHelper::generatePropertyTtsText($property, $request->user());
            $property->recording_id = GeneralHelper::savePropertyRecording($property, $request->user());

            if ($request->has('suitable_hall')) {
                $property->second_tts_text = GeneralHelper::generatePropertySecondTtsText($property, $request->user());
                $property->second_recording_id = GeneralHelper::savePropertySecondRecording($property, $request->user());

                //Artisan::call('rs_tts:update_audios');
            } /*else {
                try {
                    Artisan::call('rs_tts:update_audios', ['--recordingId' => $property->recording_id]);
                } catch (\Exception $exception) {
                    //$catch = $exception->getMessage();
                }
            }*/

            $property->save();
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('property.error.saving') . '::' . $e->getMessage());
        }
    }

    public function check_add(AddEditProperty $request)
    {
        try {
            $property = new RsProperty();
            $property->owner_id = $request->input('owner_id');
            $property->phone_number = $request->input('phone_number');
            $property->title = $request->input('title');
            $property->property_type = $request->input('property_type');
            $property->zipcode_id = $request->input('zipcode_id');
            $property->street_name = $request->input('street_name');
            $property->house_number = $request->input('house_number');
            $property->apt_number = $request->input('apt_number');
            $property->price = $request->input('price');
            $property->guest_count = $request->input('guest_count');
            $property->bed_count = $request->input('bed_count');
            $property->bedroom_count = $request->input('bedroom_count');
            $property->bathroom_count = $request->input('bathroom_count');

            $property->when_is_available = $request->input('when_is_available');
            $property->how_long_is_available = 100000; //$request->input('how_long_is_available');

            if(!is_null_or_empty($property->how_long_is_available)) {
                $property->how_long_start = Carbon::now()->startOfDay();
                $property->how_long_end = Carbon::now()->addMonths($property->how_long_is_available);
            }

            $property->cancellation_type = $request->input('cancellation_type');
            $property->cancellation_cut = $request->input('cancellation_cut');
            $property->additional_luxury = $request->input('additional_luxury');
            $property->additional_information = $request->input('additional_information');
            $property->map_lat = $request->input('map_lat');
            $property->map_lng = $request->input('map_lng');
            $property->map_address = $request->input('map_address');
            $property->billing_mode = $request->input('billing_mode');

            $property->active = $request->has('active');

            $property->couple_quantity = $request->input('couple_quantity');
            $property->floor_location = $request->input('floor_location');

            if($request->has('suitable_hall')) {
                $property->second_property_type = PropertyType::SHORT_TERM_COM_HALL;
                $property->hall_price = $request->input('hall_price');
            }

            $property->has_elevator = $request->has('has_elevator');

            //$property->save();

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('property.error.saving') . '::' . $e->getMessage());
        }
    }

    public function edit(AddEditProperty $request, $id = null)
    {
        try {
            $changes = 0;
            $property = $this->properties($request->user())->findOrFail($id);

            if ($request->input('owner_id') != $property->owner_id) {
                $property->owner_id = $request->input('owner_id');
                $changes++;
            }

            if($request->input('phone_number') != $property->phone_number){
                $property->phone_number = $request->input('phone_number');
                $changes++;
            }

            if ($request->input('title') != $property->title) {
                $property->title = $request->input('title');
                $changes++;
            }

            if ($request->input('property_type') != $property->property_type) {
                $property->property_type = $request->input('property_type');
                $changes++;
            }

            if ($request->input('zipcode_id') != $property->zipcode_id) {
                $property->zipcode_id = $request->input('zipcode_id');
                $changes++;
            }

            if ($request->input('street_name') != $property->street_name) {
                $property->street_name = $request->input('street_name');
                $changes++;
            }

            if ($request->input('house_number') != $property->house_number) {
                $property->house_number = $request->input('house_number');
                $changes++;
            }

            if ($request->input('apt_number') != $property->apt_number) {
                $property->apt_number = $request->input('apt_number');
                $changes++;
            }

            if ($request->input('price') != $property->price) {
                $property->price = $request->input('price');
                $changes++;
            }

            if ($request->input('guest_count') != $property->guest_count) {
                $property->guest_count = $request->input('guest_count');
                $changes++;
            }

            if ($request->input('bed_count') != $property->bed_count) {
                $property->bed_count = $request->input('bed_count');
                $changes++;
            }

            if ($request->input('bedroom_count') != $property->bedroom_count) {
                $property->bedroom_count = $request->input('bedroom_count');
                $changes++;
            }

            if ($request->input('bathroom_count') != $property->bathroom_count) {
                $property->bathroom_count = $request->input('bathroom_count');
                $changes++;
            }

            if($request->input('when_is_available') != $property->when_is_available) {
                $property->when_is_available = $request->input('when_is_available');
                $changes++;
            }

            if($request->input('how_long_is_available') != $property->how_long_is_available) {
                $property->how_long_is_available = 100000; //$request->input('how_long_is_available');

                if(!is_null_or_empty($property->how_long_is_available)) {
                    $property->how_long_start = Carbon::now()->startOfDay();
                    $property->how_long_end = Carbon::now()->addMonths($property->how_long_is_available);
                }

                $changes++;
            }

            if ($request->input('cancellation_type') != $property->cancellation_type) {
                $property->cancellation_type = $request->input('cancellation_type');
                $changes++;
            }

            if ($request->input('cancellation_cut') != $property->cancellation_cut) {
                $property->cancellation_cut = $request->input('cancellation_cut');
                $changes++;
            }

            if ($request->input('property_option_group') != $property->property_option_group) {
                $property->property_option_group = $request->input('property_option_group');
                $changes++;
            }

            if ($request->input('additional_luxury') != $property->additional_luxury) {
                $property->additional_luxury = $request->input('additional_luxury');
                $changes++;
            }

            if ($request->input('additional_information') != $property->additional_information) {
                $property->additional_information = $request->input('additional_information');
                $changes++;
            }

            if ($request->input('map_lat') != $property->map_lat) {
                $property->map_lat = $request->input('map_lat');
                $changes++;
            }

            if ($request->input('map_lng') != $property->map_lng) {
                $property->map_lng = $request->input('map_lng');
                $changes++;
            }

            if ($request->input('map_address') != $property->map_address) {
                $property->map_address = $request->input('map_address');
                $changes++;
            }

            if ($request->input('couple_quantity') != $property->couple_quantity) {
                $property->couple_quantity = $request->input('couple_quantity');
                $changes++;
            }

            if ($request->input('floor_location') != $property->floor_location) {
                $property->floor_location = $request->input('floor_location');
                $changes++;
            }

            if ($request->has('has_elevator') != $property->has_elevator) {
                $property->has_elevator = $request->has('has_elevator');
                $changes++;
            }

            if ($request->has('suitable_hall') && $property->second_property_type != PropertyType::SHORT_TERM_COM_HALL) {
                $property->second_property_type = PropertyType::SHORT_TERM_COM_HALL;
                $changes++;
            }

            if (!$request->has('suitable_hall') && $property->second_property_type == PropertyType::SHORT_TERM_COM_HALL) {
                $property->second_property_type = null;
                $property->hall_price = 0;
                    $changes++;
            }

            if ($request->has('suitable_hall') && $request->input('hall_price') != $property->hall_price) {
                $property->hall_price = $request->input('hall_price');
                $changes++;
            }

            if ($request->has('active') != $property->active) {
                $property->active = $request->has('active');
                $changes++;
            }

            if ($changes > 0) {
                $property->save();
                $property->tts_text = GeneralHelper::generatePropertyTtsText($property, $request->user());
                $property->recording_id = GeneralHelper::savePropertyRecording($property, $request->user());

                if($request->has('suitable_hall')) {
                    $property->second_tts_text = GeneralHelper::generatePropertySecondTtsText($property, $request->user());
                    $property->second_recording_id = GeneralHelper::savePropertySecondRecording($property, $request->user());
                    Artisan::call('rs_tts:update_audios');
                } else {
                    try {
                        Artisan::call('rs_tts:update_audios', ['--recordingId' => $property->recording_id]);
                    } catch (\Exception $exception) {
                        //$catch = $exception->getMessage();
                    }
                }

                $property->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('property.error.saving') . '::' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id = null)
    {
        try {
            $property = $this->properties($request->user())->with(['reservations'])->findOrFail($id);
            if ($property->reservations()->where('status', ReservationStatus::ACTIVE)->count() == 0) {
                $property->delete();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonErrorResponse(trans('property.can_not_delete_current_property'));
            }
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('property.error.deleting') . '::' . $e->getMessage());
        }
    }

    public function pause(Request $request, $id = null)
    {
        try {
            $property = $this->properties($request->user())->findOrFail($id);
            if($property->is_paused == 1) {
                $property->is_paused = 0;
            } else {
                $property->is_paused = 1;
            }
            $property->save();
            return $this->jsonSuccessResponse();

        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse($e->getMessage());
        }
    }

    public function images(Request $request, $id = null, $action = null)
    {
        try {
            if ($action == 'list') {
                $images = RsPropertyImage::where('property_id', $id)->get();
                return $this->jsonSuccessResponse(['data' => $images]);
            }

            if ($action == 'upload' && $request->has('base_64')) {
                $property = RsProperty::findOrFail($id);

                $base_64 = $request->input('base_64');
                $image_output = base64ToImage($base_64, '/images/properties/image_' . time());

                $image = new RsPropertyImage();
                $image->property_id = $property->id;
                $image->url = $image_output;

                $image->save();

                return $this->jsonSuccessResponse();
            }

            if ($request->has('image_id')) {
                $image = RsPropertyImage::findOrFail($request->input('image_id'));
                $changes = 0;

                if ($action == 'croppie' && $request->has('base_64')) {
                    $base_64 = $request->input('base_64');
                    $image_output = base64ToImage($base_64, '/images/properties/thumbnail_' . time());

                    if (!GeneralHelper::isNullOrEmpty($image->thumbnail_url) && file_exists(public_path($image->thumbnail_url))) {
                        unlink(public_path($image->thumbnail_url));
                    }

                    $image->thumbnail_url = $image_output;

                    $changes++;
                }

                if ($action == 'save') {
                    $image->active = $request->input('active');
                    $image->show_on_search = $request->input('show_on_search');

                    $changes++;
                }

                if ($action == 'delete') {

                    if (!GeneralHelper::isNullOrEmpty($image->thumbnail_url) && file_exists(public_path($image->thumbnail_url))) {
                        unlink(public_path($image->thumbnail_url));
                    }
                    if (!GeneralHelper::isNullOrEmpty($image->url) && file_exists(public_path($image->url)) && !str_contains($image->url, 'images_not_found')) {
                        unlink(public_path($image->url));
                    }
                    $image->delete();
                    return $this->jsonSuccessResponse();
                }

                if ($changes > 0) {
                    $image->save();
                    return $this->jsonSuccessResponse();
                }
            }

            return $this->jsonErrorResponse(trans('general.error.action'));
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('property.error.images') . '::' . $e->getMessage());
        }
    }

    public function availabilities(Request $request, $id = null, $action = null)
    {
        try {
            $today = new \DateTime();
            if ($action == 'list') {
                $availabilities = RsPropertyAvailability::where('property_id', $id)
                    ->where('date_end', '>=', $today->format('Y-m-d'))
                    ->get();
                return $this->jsonSuccessResponse(['data' => $availabilities]);
            }

            if ($action == 'create' && $request->has('active') && $request->has('start') && $request->has('end')) {
                $property = RsProperty::findOrFail($id);

                $availability = new RsPropertyAvailability();

                $availability->active = $request->input('active');
                $availability->date_start = $request->input('start');
                $availability->date_end = $request->input('end');
                $availability->property_id = $property->id;

                $availability->save();
                return $this->jsonSuccessResponse();
            }

            if ($request->has('availability_id')) {
                $availability = RsPropertyAvailability::findOrFail($request->input('availability_id'));

                if ($action == 'delete') {
                    $availability->delete();
                    return $this->jsonSuccessResponse();
                }

                if ($action == 'save' && $request->has('active') && $request->has('start') && $request->has('end')) {
                    $availability->active = $request->input('active');
                    $availability->date_start = $request->input('start');
                    $availability->date_end = $request->input('end');

                    $availability->save();
                    return $this->jsonSuccessResponse();
                }
            }

            return $this->jsonErrorResponse(trans('general.error.action'));
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('property.error.availability') . '::' . $e->getMessage());
        }
    }

    public function restrictions(Request $request, $id = null, $action = null)
    {
        try {
            $today = new \DateTime();
            if ($action == 'list') {
                $restrictions = RsPropertyRestriction::where('property_id', $id)
                    ->get();
                return $this->jsonSuccessResponse(['data' => $restrictions]);
            }

            if ($action == 'create' && $request->has('restriction_type')) {
                $restrictionType =$request->input('restriction_type');
                $dayWeek =$request->input('day_of_week');
                $dayMonth =$request->input('day_of_month');
                $startHour =$request->input('start');
                $endHour =$request->input('end');

                if( $restrictionType != 'none') {

                    if($restrictionType == 'day_of_week' && $dayWeek != '0') {
                        $property = RsProperty::findOrFail($id);
                        $restriction = new RsPropertyRestriction();
                        $restriction->property_id = $property->id;
                        $restriction->restriction_type = $restrictionType;
                        $restriction->day_of_week = $dayWeek;
                        $restriction->save();
                        return $this->jsonSuccessResponse();
                    } else if($restrictionType == 'day_of_month' && $dayMonth != '0') {
                        $property = RsProperty::findOrFail($id);
                        $restriction = new RsPropertyRestriction();
                        $restriction->property_id = $property->id;
                        $restriction->restriction_type = $restrictionType;
                        $restriction->day_of_month = $dayMonth;
                        $restriction->save();
                        return $this->jsonSuccessResponse();
                    } else if($restrictionType == 'hour' && !is_null_or_empty($startHour) && !is_null_or_empty($endHour)) {
                        $property = RsProperty::findOrFail($id);
                        $restriction = new RsPropertyRestriction();
                        $restriction->property_id = $property->id;
                        $restriction->restriction_type = $restrictionType;
                        $restriction->start_time = $startHour;
                        $restriction->end_time = $endHour;
                        $restriction->save();
                        return $this->jsonSuccessResponse();
                    } else {
                        return $this->jsonErrorResponse(trans('general.error.action'));
                    }
                }
            }

            if ($request->has('restriction_id')) {
                $restriction = RsPropertyRestriction::findOrFail($request->input('restriction_id'));

                if ($action == 'delete') {
                    $restriction->delete();
                    return $this->jsonSuccessResponse();
                }

                if ($action == 'save' && $request->has('restriction_type')) {
                    $restrictionType =$request->input('restriction_type');
                    $dayWeek =$request->input('day_of_week');
                    $dayMonth =$request->input('day_of_month');
                    $startHour =$request->input('start');
                    $endHour =$request->input('end');

                    if( $restrictionType != 'none') {

                        if($restrictionType == 'day_of_week' && $dayWeek != '0') {
                            $restriction->restriction_type = $restrictionType;
                            $restriction->day_of_week = $dayWeek;
                            $restriction->day_of_month = null;
                            $restriction->start_time = null;
                            $restriction->end_time = null;
                            $restriction->save();
                            return $this->jsonSuccessResponse();
                        } else if($restrictionType == 'day_of_month' && $dayMonth != '0') {
                            $restriction->restriction_type = $restrictionType;
                            $restriction->day_of_month = $dayMonth;
                            $restriction->day_of_week = null;
                            $restriction->start_time = null;
                            $restriction->end_time = null;
                            $restriction->save();
                            return $this->jsonSuccessResponse();
                        } else if($restrictionType == 'hour' && !is_null_or_empty($startHour) && !is_null_or_empty($endHour)) {
                            $restriction->restriction_type = $restrictionType;
                            $restriction->start_time = $startHour;
                            $restriction->end_time = $endHour;
                            $restriction->day_of_week = null;
                            $restriction->day_of_month = null;
                            $restriction->save();
                            return $this->jsonSuccessResponse();
                        } else {
                            return $this->jsonErrorResponse(trans('general.error.action'));
                        }
                    }
                }

            }

            return $this->jsonErrorResponse(trans('general.error.action'));
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    //=========================
    public function packages(Request $request, $id = null, $action = null)
    {
        try{
            if($action == 'list'){

                $property = RsProperty::find($id);
                $diaspora = true;

                //from zipcode check if from israel
                $zc = RsZipCode::where('zipcode', $property->zipcode_id)->first();
                if ($zc){ //
                    $runWhile = true;

                    $area = RsArea::where('id', $zc->area_id)->first();

                    while($runWhile) {
                        if ($area) {
                            if($area->name == 'Eretz Israel') {
                                $diaspora = false;
                                $runWhile = false;
                            } else {
                                if($area->parent_area_id == 0) {
                                    $runWhile = false;
                                } else {
                                    $area = RsArea::where('id', $area->parent_area_id)->first();
                                }
                            }
                        } else {
                            $runWhile = false;
                        }
                    }
                }

                $calendar = new Holidays();
                $year = Carbon::now()->year;
                $jewHolidays = $calendar->getJewHolidays($year, $diaspora);
                $usHolidays = $calendar->getUsHolidays($year);

                $holidays = [];

                foreach ($jewHolidays as $holiday) {
                    $rsHoliday = RsHoliday::where('name', '=', $holiday['name'])
                        ->where('holiday_type', '=', 'jew')
                        ->where('active', 1)->first();

                    if ($rsHoliday) {
                        //check if package is set for this property and holiday
                        $checkIn = Carbon::createFromFormat('Y-m-d h:i A', $holiday['date_checkin']);
                        $checkOut = Carbon::createFromFormat('Y-m-d h:i A', $holiday['date_checkout']);

                        //====================================================================
                        //====================================================================
                        $disabledDates = [];

                        if ($holiday['name'] == 'Pesach') {
                            $disabledDates[] = $checkIn->copy()->addDays(1)->format('Y-m-d');
                            $disabledDates[] = $checkIn->copy()->addDays(2)->format('Y-m-d');
                            $disabledDates[] = $checkIn->copy()->addDays(7)->format('Y-m-d');
                            $disabledDates[] = $checkIn->copy()->addDays(8)->format('Y-m-d');
                        } else if ($holiday['name'] == 'Sukkot') {
                            $disabledDates[] = $checkIn->copy()->addDays(1)->format('Y-m-d');

                            $disabledDates[] = $checkIn->copy()->addDays(8)->format('Y-m-d');

                            if ($diaspora) {
                                $disabledDates[] = $checkIn->copy()->addDays(2)->format('Y-m-d');
                                $disabledDates[] = $checkIn->copy()->addDays(9)->format('Y-m-d');
                            }
                        } else {
                            $days = $checkOut->copy()->startOfDay()->diffInDays($checkIn->copy()->startOfDay());

                            for($i = 1; $i < $days; $i++){
                                $disabledDates[] = $checkIn->copy()->addDays($i)->format('Y-m-d');
                            }
                        }

                        //====================================================================
                        //====================================================================
                        $packages = [];

                        $propPackages = RsPropertyPackage::where('rs_property_id', '=', $id)
                            ->where('rs_holiday_id', '=', $rsHoliday->id)->get();

                        foreach ($propPackages as $propPackage) {
                            $packages[] = [
                                'package_id' => $propPackage->id,
                                'date_checkin' => $propPackage->checkin_date,
                                'date_checkout' => $propPackage->checkout_date,
                                'price' => $propPackage->price,
                                'active' => $propPackage->active
                            ];
                        }

                        //====================================================================
                        //====================================================================

                        $holidays[] = [
                            'property_id' => $id,
                            'holiday_id' => $rsHoliday->id,
                            'days' => $checkOut->copy()->startOfDay()->diffInDays($checkIn->copy()->startOfDay()),
                            'holiday_type' => $rsHoliday->holiday_type,
                            'name' => $rsHoliday->name,
                            'date_start' => $holiday['date_start'],
                            'date_end' => $holiday['date_end'],
                            'date_checkin' => $checkIn->format('Y-m-d'),
                            'date_checkout' => $checkOut->format('Y-m-d'),
                            'packages' => $packages,
                            'disabled_dates' => $disabledDates
                        ];
                    }
                }

                foreach ($usHolidays as $holiday) {
                    $rsHoliday = RsHoliday::where('name', '=', $holiday['name'])
                        ->where('holiday_type', '=', 'us')
                        ->where('active', 1)->first();

                    if($rsHoliday){
                        //check if package is set for this property and holiday
                        $checkIn = Carbon::createFromFormat('Y-m-d h:i A', $holiday['date_checkin']);
                        $checkOut = Carbon::createFromFormat('Y-m-d h:i A', $holiday['date_checkout']);

                        $disabledDates = [];

                        $packages = [];

                        $propPackages = RsPropertyPackage::where('rs_property_id', '=', $id)
                            ->where('rs_holiday_id', '=', $rsHoliday->id)->get();

                        foreach ($propPackages as $propPackage) {
                            $packages[] = [
                                'package_id' => $propPackage->id,
                                'date_checkin' => $propPackage->checkin_date,
                                'date_checkout' => $propPackage->checkout_date,
                                'price' => $propPackage->price,
                                'active' => $propPackage->active
                            ];
                        }

                        $holidays[] = [
                            'property_id' => $id,
                            'holiday_id' => $rsHoliday->id,
                            'days' => $checkOut->copy()->startOfDay()->diffInDays($checkIn->copy()->startOfDay()),
                            'holiday_type' => $rsHoliday->holiday_type,
                            'name' => $rsHoliday->name,
                            'date_start' => $holiday['date_start'],
                            'date_end' => $holiday['date_end'],
                            'date_checkin' => $checkIn->format('Y-m-d'),
                            'date_checkout' => $checkOut->format('Y-m-d'),
                            'packages' => $packages,
                            'disabled_dates' => $disabledDates
                        ];
                    }
                }

                //=========================================
                //=========================================
                $rsHolidays = RsHoliday::where('holiday_type', '=', 'weekend')
                    ->where('active', 1)->get();

                foreach($rsHolidays as $rsHoliday){
                    //check if package is set for this property and holiday
                    $checkIn = Carbon::now()->startOfDay();
                    $checkOut = $checkIn->copy()->addDays(2)->endOfDay();

                    $disabledDates = [];

                    $packages = [];

                    $propPackages = RsPropertyPackage::where('rs_property_id', '=', $id)
                        ->where('rs_holiday_id', '=', $rsHoliday->id)->get();

                    foreach ($propPackages as $propPackage) {
                        $packages[] = [
                            'package_id' => $propPackage->id,
                            'date_checkin' => $propPackage->checkin_date,
                            'date_checkout' => $propPackage->checkout_date,
                            'price' => $propPackage->price,
                            'active' => $propPackage->active
                        ];
                    }

                    $holidays[] = [
                        'property_id' => $id,
                        'holiday_id' => $rsHoliday->id,
                        'days' => $checkOut->copy()->startOfDay()->diffInDays($checkIn->copy()->startOfDay()),
                        'holiday_type' => $rsHoliday->holiday_type,
                        'name' => $rsHoliday->name,
                        'date_start' => $checkIn->format('Y-m-d'),
                        'date_end' => $checkOut->format('Y-m-d'),
                        'date_checkin' => $checkIn->format('Y-m-d'),
                        'date_checkout' => $checkOut->format('Y-m-d'),
                        'packages' => $packages,
                        'disabled_dates' => $disabledDates
                    ];
                }
                //=========================================
                //=========================================
                $allWeekend = array();

                $rsWeekendHoliday1 = RsHoliday::where('holiday_type', '=', 'all_weekend_1')
                    ->where('active', 1)->first();

                $propPackage = RsPropertyPackage::where('rs_property_id', '=', $id)
                    ->where('rs_holiday_id', '=', $rsWeekendHoliday1->id)->first();

                if($propPackage) {
                    $allWeekend[] = [
                        'package_id' => 1,
                        'price' => $propPackage->price,
                        'active' => $propPackage->active
                    ];
                } else {
                    $allWeekend[] = [
                        'package_id' => 1,
                        'price' => '',
                        'active' => null
                    ];
                }

                $rsWeekendHoliday2 = RsHoliday::where('holiday_type', '=', 'all_weekend_2')
                    ->where('active', 1)->first();

                $propPackage = RsPropertyPackage::where('rs_property_id', '=', $id)
                    ->where('rs_holiday_id', '=', $rsWeekendHoliday2->id)->first();

                if($propPackage) {
                    $allWeekend[] = [
                        'package_id' => 2,
                        'price' => $propPackage->price,
                        'active' => $propPackage->active
                    ];
                } else {
                    $allWeekend[] = [
                        'package_id' => 2,
                        'price' => '',
                        'active' => null
                    ];
                }
                //=========================================
                //=========================================

                return $this->jsonSuccessResponse(['data' => $holidays, 'all_weekend' => $allWeekend]);
            }

            if($action == 'save'){
                $propertyId = $request->input('property_id');
                $holidayId = $request->input('holiday_id');
                $packageId = $request->input('package_id');

                $price = $request->input('price');
                $active = ($request->has('active') ? $request->input('active') : false);

                $response = $packageId;

                if($holidayId == 0) {
                    $checkIn = Carbon::today()->startOfDay();
                    $checkOut = Carbon::today()->startOfDay()->addDays(1)->endOfDay();

                    //search id for all_weekend holiday
                    $holidayId = RsHoliday::where('holiday_type', '=', 'all_weekend_' . $packageId)->first()->id;

                    //check if package is set for this property and holiday
                    $propPackage = RsPropertyPackage::where('rs_property_id', '=', $propertyId)
                        ->where('rs_holiday_id', '=', $holidayId)->first();

                    if(!$propPackage){
                        $propPackage = new RsPropertyPackage();
                        $propPackage->rs_property_id = $propertyId;
                        $propPackage->rs_holiday_id = $holidayId;
                        $propPackage->checkin_date = $checkIn->format('Y-m-d');
                        $propPackage->checkout_date = $checkOut->format('Y-m-d');
                        $propPackage->price = $price;
                        $propPackage->active = $active;
                        $propPackage->save();
                    } else {
                        $propPackage->price = $price;
                        $propPackage->active = $active;
                        $propPackage->save();
                    }

                    $response = $propPackage->id;
                } else {
                    $checkIn = Carbon::createFromFormat('Y-m-d', $request->input('check_in'));
                    $checkOut = Carbon::createFromFormat('Y-m-d', $request->input('check_out'));

                    if($packageId == '0') {
                        $pac = new RsPropertyPackage();
                        $pac->rs_property_id = $propertyId;
                        $pac->rs_holiday_id = $holidayId;
                        $pac->checkin_date = $checkIn->format('Y-m-d');
                        $pac->checkout_date = $checkOut->format('Y-m-d');
                        $pac->price = $price;
                        $pac->active = $active;
                        //todo generate tts_text
                        $pac->save();

                        $response = $pac->id;

                    } else {
                        $pac = RsPropertyPackage::where('id', '=', $packageId)->first();

                        if($pac) {
                            $pac->checkin_date = $checkIn->format('Y-m-d');
                            $pac->checkout_date = $checkOut->format('Y-m-d');
                            $pac->price = $price;
                            $pac->active = $active;
                            //todo generate tts_text
                            $pac->save();

                            $response = $pac->id;
                        }
                    }
                }

                return $this->jsonSuccessResponse(['package_id' => $response]);
            }

            return $this->jsonErrorResponse(trans('general.error.action'));
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e->getMessage());
        }
    }

    public function mapLocation(Request $request, $id = null)
    {
        try {
            $property = RsProperty::findOrFail($id);

            $property->map_lat = $request->input('lat');
            $property->map_lng = $request->input('lng');
            $property->map_address = $request->input('address');

            $property->save();

            return $this->jsonSuccessResponse();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('property.error.saving') . '::' . $e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $list = $this->properties($request->user())
            ->with([
                'owner' => function ($query) {
                    $query->select(['id', 'last_name', 'first_name', 'phone_number']);
                }
            ]);

        return Datatables::of($list)
            ->addColumn('cancellation_type_text', function ($data) {
                return trans('property.cancellation.' . $data->cancellation_type);
            })
            ->addColumn('property_type_text', function ($data) {
                return trans('property.type.' . $data->property_type);
            })
            ->addColumn('owner_full_name', function ($data) {
                return GeneralHelper::getUserFullName($data->owner, trans('profile.format.full_name'), trans('general.n_a'));
            })
            ->addColumn('owner_phone_number', function ($data) {
                return (isset($data->owner) ? $data->owner->phone_number : trans('general.n_a'));
            })
            ->addColumn('active_text', function ($data) {
                return $data->active ? trans('general.yes') : trans('general.no');
            })
            ->addColumn('embed_link_code', function ($data) {
                return $this->propertyDirectLink($data->id);
            })
            ->addColumn('embed_link', function ($data) {
                return $this->propertyDirectLink($data->id, true);
            })
            ->addIndexColumn()
            ->setRowId('id')
            ->make(true);
    }

    private function properties($user)
    {
        return $user->is_broker
            ? RsProperty::query() //->with('propertyCriteria.searchCriterion.criterionType')
            : RsProperty::where('owner_id', $user->id);
    }

    public function criteriaDatatable(Request $request, $id = null)
    {
        $list = RsPropertyCriterion::with('searchCriterion.criterionType')
            ->where('property_id', $id)->get();

        /*$list = DB::select('select a.*, b.name criterionName, c.name criterionTypeName
                              from rs_property_criteria a,
                                   rs_search_criteria b,
                                   rs_criterion_types c
                             where a.criterion_id = b.id
                               and b.criterion_type_id = c.id
                               and a.deleted_at is null
                               and a.property_id = ' . $id);*/

        return Response::json($list);
    }

    public function saveCriteria(Request $request, $id = null)
    {
        try {
            $criterion = RsSearchCriterion::where('id', $request->input('criterion_id'))->first();

            $propCriterion = new RsPropertyCriterion();
            $propCriterion->property_id = $request->input('property_id');
            $propCriterion->criterion_id = $request->input('criterion_id');

            if ($criterion->has_quantity) {
                $propCriterion->has_quantity = $criterion->has_quantity;
                $propCriterion->quantity = $request->input('quantity');
            }

            if ($criterion->has_distance) {
                $propCriterion->has_distance = $criterion->has_distance;
                $propCriterion->walking_distance = $request->input('walking_distance');
                $propCriterion->driving_distance = $request->input('driving_distance');
            }

            $propCriterion->save();
            return Response::json($propCriterion);

        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('criteria.error.criterion_saving') . '::' . $e->getMessage());
        }
    }

    public function deleteCriteria(Request $request, $id = null, $cid = null)
    {

        $criterion = RsPropertyCriterion::where('property_id', $id)
            ->where('criterion_id', $cid)->first();

        if ($criterion) {
            $criterion->delete();
        }

        return Response::json($criterion);
    }

    public function fetchStreetNames(Request $request, $id = null)
    {
        $name = $request->input('query');
        $streets = GeneralHelper::getZipcodeStreetNames($id, $name);

        return Response::json($streets);
    }

    public function fetchAreaName(Request $request, $id = null)
    {
        $zipCode = RsZipCode::where('zipcode', $id)->first();
        if($zipCode) {
            $area = RsArea::where('id', $zipCode->area_id)->first();

            if (!$area) {
                $area = array();
                $area['name'] = $zipCode->city;
            }
        } else {
            $area = array();
            $area['name'] = '';
        }
        return Response::json($area);
    }
}