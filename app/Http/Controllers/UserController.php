<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\AddUser;
use App\Http\Requests\EditUser;
use SMD\Common\ReservationSystem\Enums\NotificationType;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use App\RsUser;
use Yajra\Datatables\Facades\Datatables;

class UserController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('ajax.json')->only(['add', 'edit', 'delete']);
    }

    public function show($id = null)
    {
        $with = [
            'roles' => RoleType::ALL,
            'payment_vias' => RsPaymentVia::TYPES
        ];

        if (is_null($id)) {
            return view('users')->with($with);
        } else {
            try {
                $with['edit_user'] = $this->users()->findOrFail($id);
                return view('users')->with($with);
            } catch (ModelNotFoundException $e) {
                return $this->notFoundResponse();
            } catch (\Exception $e) {
                return $this->errorProcessingResponse(trans('general.error.processing_request') . ': ' . $e->getMessage());
            }
        }
    }

    public function add(AddUser $request)
    {
        try {
            $user = new RsUser();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            //Todo: Check if a default email is needed
            $user->email = $request->input('email');
            $user->role_id = $request->input('role_id');
            $user->password = bcrypt($request->input('password'));
            $user->phone_number = $request->input('phone_number');
            $user->pin = $request->input('pin');
            $user->activated = $request->has('activated');
            if (!GeneralHelper::isNullOrEmpty($request->input('payment_via'))) {
                $user->payment_via = $request->input('payment_via');
            }
            if ($user->role_id == RoleType::OWNER) {
                $user->commission = $request->input('commission');
            } else {
                $user->commission = 0;
            }
            GeneralHelper::stripeCustomerUpdate($user);
            $user->save();
            try {
                GeneralHelper::userNotification($user, NotificationType::USER_CREATED_FOR_YOU);
            } catch (\Exception $ex) {
                GeneralHelper::userLog('notification[new-user]', $request->user(), $ex);
            }
            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('user.error_saving') . '::' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id = null)
    {
        try {
            $user = $this->users()->findOrFail($id);
            if ($user->id != $request->user()->id) {
                $user->delete();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonErrorResponse(trans('user.can_not_delete_current_user'));
            }
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('user.error_deleting') . '::' . $e->getMessage());
        }
    }

    public function edit(EditUser $request, $id = null)
    {
        try {
            $changes = 0;
            $user = $this->users()->findOrFail($id);

            if ($request->input('first_name') != $user->first_name) {
                $user->first_name = $request->input('first_name');
                $changes++;
            }

            if ($request->input('last_name') != $user->last_name) {
                $user->last_name = $request->input('last_name');
                $changes++;
            }

            if ($request->input('role_id') != $user->role_id) {
                $user->role_id = $request->input('role_id');
                $changes++;
            }

            if ($request->has('activated') != $user->activated) {
                $user->activated = $request->has('activated');
                $changes++;
            }

            if (!is_null($request->input('password')) && $request->input('password') != $user->password) {
                $user->password = bcrypt($request->input('password'));
                $changes++;
            }

            if (!is_null($request->input('pin')) && $request->input('pin') != $user->pin) {
                $user->pin = $request->input('pin');
                $changes++;
            }

            if ($user->commission != $request->input('commission')) {
                if ($user->role_id == RoleType::OWNER) {
                    $user->commission = $request->input('commission');
                } else {
                    $user->commission = 0;
                }
                $changes++;
            }

            if ($user->payment_via != $request->input('payment_via')) {
                $user->payment_via = $request->input('payment_via');
                $changes++;
            }

            if ($changes > 0) {
                GeneralHelper::stripeCustomerUpdate($user);
                $user->save();
                if (!$user->activated) {
                    try {
                        GeneralHelper::userNotification($user, NotificationType::USER_DISABLED);
                    } catch (\Exception $ex) {
                        GeneralHelper::userLog('notification[user-disabled]', $request->user(), $ex);
                    }
                }
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->errorProcessingResponse(trans('user.error_saving') . '::' . $e->getMessage());
        }
    }

    public function datatable()
    {
        return Datatables::of($this->users())
            ->addColumn('role_name', function ($data) {
                return trans('user.roles.' . $data->role_id);
            })
            ->addColumn('activated_text', function ($data) {
                return $data->activated ? trans('general.yes') : trans('general.no');
            })
            ->addIndexColumn()
            ->setRowId('id')
            ->make(true);
    }

    private function users()
    {
        return RsUser::query();
    }
}
