<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePhoneLocation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsUserPhoneLocation;
use Yajra\Datatables\Facades\Datatables;

class PhoneLocationController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only(['update', 'delete']);
    }

    public function show(Request $request, $id = null)
    {
        $with = [];

        if (is_null($id)) {
            return view('addresses');
        } else {
            try {
                $with['edit_address'] = $this->addresses($request->user())->findOrFail($id);
                return view('addresses')->with($with);
            } catch (ModelNotFoundException $e) {
                return $this->notFoundResponse();
            } catch (\Exception $e) {
                return $this->errorProcessingResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
            }
        }
    }

    public function update(UpdatePhoneLocation $request)
    {
        try {
            if ($request->has('id') && !GeneralHelper::isNullOrEmpty($request->input('id'))) {
                $address = RsUserPhoneLocation::findOrFail($request->input('id'));
            } else {
                $address = new RsUserPhoneLocation();
                $address->phone_number = $request->input('phone_number');
                $address->user_id = $request->user()->id;
            }

            $address->name = $request->input('name');
            $address->house_number = $request->input('house_number');
            $address->street_name = $request->input('street_name');
            $address->apt_number = $request->input('apt_number');
            $address->city = $request->input('city');
            $address->state = $request->input('state');
            $address->country = $request->input('country');
            $address->postal_code = $request->input('postal_code');
            //$address->zip4 = $request->input('zip4');
            $address->is_commercial = $request->has('is_commercial');
            $address->is_valid = true;

            $address->save();

            $address->tts_text = GeneralHelper::generateLookupTtsText($address, $request->user());
            $address->recording_id = GeneralHelper::saveLookupRecording($address, $request->user());

            $address->long_tts_text = GeneralHelper::generateLookupLongTtsText($address, $request->user());
            $address->long_recording_id = GeneralHelper::saveLookupLongRecording($address, $request->user());

            $address->save();
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('address.error.saving') . '::' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id = null)
    {
        try {
            $address = $this->addresses($request->user())->findOrFail($id);
            $address->delete();

            return $this->jsonSuccessResponse();
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('property.error.deleting') . '::' . $e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $list = $this->addresses($request->user());

        return Datatables::of($list)
            ->addColumn('full_address', function ($data) {
                $pieces = [];

                if (!GeneralHelper::isNullOrEmpty($data->house_number)) {
                    $pieces[] = $data->house_number;
                }

                if (!GeneralHelper::isNullOrEmpty($data->street_name)) {
                    $pieces[] = $data->street_name;
                }

                if (!GeneralHelper::isNullOrEmpty($data->apt_number)) {
                    $pieces[] = ', apt. ' . $data->apt_number;
                }

                $parts = [];

                if (!GeneralHelper::isNullOrEmpty($data->city)) {
                    $parts[] = $data->city;
                }

                if (!GeneralHelper::isNullOrEmpty($data->state)) {
                    $parts[] = $data->state;
                }

                if (!GeneralHelper::isNullOrEmpty($data->country)) {
                    $parts[] = $data->country;
                }

                /*if (!GeneralHelper::isNullOrEmpty($data->zip4)) {
                        $parts[] =  $data->zip4;
                }*/

                $pieces[] = join(', ', $parts);

                if (!GeneralHelper::isNullOrEmpty($data->postal_code)) {
                    $pieces[] = $data->postal_code;
                }

                return join(' ', $pieces);
            })
            ->addColumn('user_text', function ($data) {
                return GeneralHelper::getUserFullName($data->user, trans('profile.format.full_name'), trans('general.n_a'));
            })
            ->addColumn('is_commercial_text', function ($data) {
                return $data->is_commercial ? trans('general.yes') : trans('general.no');
            })
            ->addIndexColumn()
            ->setRowId('id')
            ->make(true);
    }

    private function addresses($user)
    {
        return !$user->is_broker
            ? RsUserPhoneLocation::where('user_id', $user->id)
            : RsUserPhoneLocation::query();
    }
}