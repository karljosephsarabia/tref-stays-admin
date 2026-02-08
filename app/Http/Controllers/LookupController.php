<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsProperty;
use App\RsUser;
use SMD\Common\ReservationSystem\Models\RsSearchCriterion;
use SMD\Common\ReservationSystem\Models\RsZipCode;

class LookupController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only([
            'location', 'localityReserve', 'users', 'reservedDates', 'addressLookup'
        ]);
    }

    public function users(Request $request, $role)
    {
        try {
            $results = [];

            if ($request->has('search') || $request->has('all')) {
                $search = $request->input('search');
                $like = $search . '%';

                $users = RsUser::active();

                $is_role = in_array($role, RoleType::ALL);

                if ($is_role) {
                    $users = $users->where('role_id', $role);
                }

                if (!GeneralHelper::isNullOrEmpty($search)) {
                    $users = $users->whereRaw('(`first_name` like ? or `last_name` like ?)', [$like, $like]);
                }

                $users = $users->select(['id', 'first_name', 'last_name', 'role_id']);

                foreach ($users->get() as $user) {
                    $item = [
                        'id' => $user->id,
                        'text' => GeneralHelper::getUserFullName($user, trans('profile.format.full_name'), trans('general.n_a'))
                    ];

                    if (!$is_role) {
                        $item['role'] = trans('user.roles.' . $user->role_id);
                    }

                    $results[] = $item;
                }
            }

            return $this->jsonSuccessResponse(['results' => $results]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function reservedDates(Request $request, $id)
    {
        try {
            $property = RsProperty::active()->findOrFail($id);

            $result = $property->reservedDates()->get();

            if ($request->user() == null) {
                $result = collect($result)
                    ->map(function ($item) {
                        unset($item['customer_id']);
                        return $item;
                    });
            }

            return $this->jsonSuccessResponse(['data' => $result]);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function zipcodeLookup(Request $request)
    {
        try {
            $results = [];

            if ($request->has('search')) {
                $search = $request->input('search');

                $results = RsZipCode::active()
                    ->whereRaw('(`zipcode` like ?)', ["{$search}%"])
                    ->orWhereRaw('(`city` like ?)', ["%{$search}%"])
                    ->orWhereRaw('(`state` like ?)', ["%{$search}%"])
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->zipcode,
                            'text' => $item->zipcode . ' ' . $item->city . ', ' . $item->state . ', ' . $item->country
                        ];
                    })->toArray();
            }

            return $this->jsonSuccessResponse(['results' => $results]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function amenityLookup(Request $request, $type = 'Appliance')
    {
        try {
            $results = [];

            if ($request->has('search')) {
                $search = $request->input('search');

                $results = \DB::table('rs_search_criteria as sc')
                    ->join('rs_criterion_types as ct', 'sc.criterion_type_id', '=', 'ct.id')
                    ->select(['sc.id', 'sc.name', 'ct.name as group'])
                    ->whereRaw('(ct.name like ?)', ["%{$search}%"])
                    ->orWhereRaw('(sc.name like ?)', ["%{$search}%"])
                    ->get()
                    ->groupBy('group')
                    ->map(function ($item, $key) {
                        return [
                            'text' => $key,
                            'children' => $item->map(function ($child) {
                                return [
                                    'id' => $child->id,
                                    'text' => $child->name
                                ];
                            })->toArray()
                        ];
                    })->toArray();
            }

            return $this->jsonSuccessResponse(['results' => $results]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function reverseAmenityLookup(Request $request)
    {
        $amenities = (array)$request->input('amenities', []);

        try {
            $results = RsSearchCriterion::whereIn('id', $amenities)->get()
                ->map(function ($item){
                    return [
                        'id' => $item->id,
                        'text' => $item->name
                    ];
                })
                ->toArray();

            return $this->jsonSuccessResponse(['results' => $results]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function reverseZipcodeLookup(Request $request, $zipcode)
    {
        try {
            $results = [];

            $zipcode_db = RsZipCode::whereZipcode($zipcode)->firstOrFail();

            $results['zipcode'] = $zipcode_db->zipcode;
            $results['city'] = $zipcode_db->city;
            $results['state'] = $zipcode_db->state;
            $results['country'] = $zipcode_db->country;

            return $this->jsonSuccessResponse(['results' => $results]);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
        }
    }

    public function addressLookup(Request $request, $number = null)
    {
        if (GeneralHelper::isNullOrEmpty($number)) {
            return $this->jsonErrorResponse(trans('address.error.number'));
        }

        try {
            $refresh_days = config('app.min_whitepages_refresh_days', 15);
            $address = GeneralHelper::lookupNumber($request->user(), $number, true, $refresh_days);

            if ($address->is_valid) {
                return $this->jsonSuccessResponse([
                    'id' => $address->id,
                    'phone_number' => $address->phone_number,
                    'name' => $address->name,
                    'street_name' => $address->street_name,
                    'city' => $address->city,
                    'state' => $address->state,
                    'country' => $address->country,
                    'postal_code' => $address->postal_code,
                    'zip4' => $address->zip4,
                    'is_commercial' => $address->is_commercial
                ]);
            }
            return $this->jsonErrorResponse(trans('address.error.invalid_address_lookup_data'));
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('address.error.looking_up_address') . '::' . $e->getMessage());
        }
    }
}