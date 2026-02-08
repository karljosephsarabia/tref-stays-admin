<?php

namespace App\Http\Controllers;

use App\RsUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsReservation;
use Yajra\Datatables\Facades\Datatables;

class ReservationController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only(['reserve', 'cancel', 'actions']);
    }

    public function index()
    {
        $items = [
            ['name' => trans('general.reservations'), 'route' => route('reservations'), 'class' => 'active']
        ];

        $statuses = [];

        foreach (ReservationStatus::STATUSES as $status) {
            $statuses[] = [
                'id' => $status,
                'text' => trans('reservation.status.' . $status)
            ];
        }

        return view('reservations')
            ->with([
                'title' => trans('reservation.show_title'),
                'statuses' => $statuses,
                'items' => $items
            ]);
    }

    public function details(Request $request, $id)
    {
        try {
            $reservation = RsReservation::forUser($request->user())->findOrFail($id);

            $items = [
                ['name' => trans('general.reservations'), 'route' => route('reservations')],
                ['name' => trans('general.details')],
                ['name' => $reservation->property->title, 'class' => 'active']
            ];

            return view('reservations')
                ->with([
                    'activity' => $reservation->activities->last(),
                    'title' => trans('general.reservation'),
                    'items' => $items,
                    'reservation' => $reservation
                ]);
        } catch (ModelNotFoundException $e) {
            GeneralHelper::userLog('processing_request', $request->user(), $e);
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            GeneralHelper::userLog('processing_request', $request->user(), $e);
            return $this->errorProcessingResponse(trans('general.error.processing_request'));
        }
    }

    public function reserve(Request $request, $id)
    {
        try {
            $user = $request->user();
            $start_date = $request->input('check_in');
            $end_date = $request->input('check_out');
            $customer_id = $request->input('customer_id');
            $source_card = $request->input('source_card');
            $guest_count = $request->input('guest_count');

            $result = GeneralHelper::doReservation($id, $start_date, $end_date, $guest_count, $user, $customer_id, $source_card);

            if (isset($result['error'])) {
                return $this->jsonNoChangeRequiredResponse(null, trans('reservation.' . $result['error']));
            }

            return $this->jsonSuccessResponse($result);
        } catch (\Exception $e) {
            GeneralHelper::userLog('processing_request', $request->user(), $e);
            return $this->jsonErrorResponse(trans('reservation.error.reserving'));
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            $user = $request->user();
            $observation = $request->input('observation');

            $result = GeneralHelper::doCancellationAndRefund($id, $user, $observation);

            if (isset($result['error'])) {
                return $this->jsonNoChangeRequiredResponse(null, trans('reservation.' . $result['error']));
            }

            return $this->jsonSuccessResponse($result);
        } catch (\Exception $e) {
            GeneralHelper::userLog('processing_request', $request->user(), $e);
            return $this->jsonErrorResponse(trans('reservation.error.cancelling') . ':: ' . $e->getMessage());
        }
    }

    public function status(Request $request, $id)
    {
        try {
            $user = $request->user();
            if ($request->has('status')) {
                $status = $request->input('status');
                $result = GeneralHelper::changeStatus($id, $status, $user);

                if (isset($result['error'])) {
                    return $this->jsonNoChangeRequiredResponse(null, trans('reservation.' . $result['error']));
                }

                return $this->jsonSuccessResponse([
                    'title' => trans('reservation.status.' . $result['status']),
                    'text' => trans('reservation.successful.' . $result['status'])
                ]);
            }
            return $this->jsonErrorResponse(trans('general.error.action'));
        } catch (\Exception $e) {
            GeneralHelper::userLog('processing_request', $request->user(), $e);
            return $this->jsonErrorResponse(trans('reservation.error.changing_status'));
        }
    }

    public function datatable(Request $request)
    {
        try {
            $reservations = RsReservation::forUser($request->user())
                ->where('status', '!=', ReservationStatus::ACTIVE);

            return Datatables::of($reservations)
                ->addColumn('reservation_status', function ($data) {
                    return trans('reservation.status.' . $data->status);
                })
                ->addColumn('owner_full_name', function ($data) {
                    $property = \DB::table('rs_properties')->where('id', $data->property_id)->first();
                    $owner = \DB::table('rs_users')->where('id', $property->owner_id)->first();
                    return GeneralHelper::getUserFullName($owner, trans('profile.format.full_name'), trans('general.n_a'));
                })
                ->addColumn('broker_full_name', function ($data) {
                    $broker = RsUser::whereId($data->broker_id)->first();
                    return GeneralHelper::getUserFullName($broker, trans('profile.format.full_name'), trans('general.n_a'));
                })
                ->addColumn('customer_full_name', function ($data) {
                    $customer = RsUser::whereId($data->customer_id)->first();
                    return GeneralHelper::getUserFullName($customer, trans('profile.format.full_name'), trans('general.n_a'));
                })
                ->addColumn('property_type_text', function ($data) {
                    $property = \DB::table('rs_properties')->where('id', $data->property_id)->first();
                    return trans('property.type.' . $property->property_type);
                })
                ->addColumn('property_title', function ($data) {
                    $property = \DB::table('rs_properties')->where('id', $data->property_id)->first();
                    return $property->title;
                })
                ->addIndexColumn()
                ->setRowId('id')
                ->make(true);
        } catch (\Exception $e) {
            GeneralHelper::userLog('ReservationController datatable', $request->user(), $e);
            return $this->errorProcessingResponse(trans('general.error.processing_request'));
        }
    }
}
