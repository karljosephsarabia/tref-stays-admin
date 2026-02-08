<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddSource;
use App\Http\Requests\EditSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests\EditProfile;
use Illuminate\Support\Facades\Response;
use SMD\Common\BillingSystem\Enums\BsChargeStatus;
use SMD\Common\BillingSystem\Enums\BsNotificationType;
use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsCreditCard;
use SMD\Common\ReservationSystem\Models\RsReservation;
use App\RsUser;
use SMD\Common\ReservationSystem\Models\RsUserFeeConfiguration;
use SMD\Common\Stripe\Api\Customer;
use SMD\Common\Stripe\Api\Source;
use SMD\Common\Stripe\Enums\SourceType;
use SMD\Common\Traits\StripeTrait;

class AccountController extends AppBaseController
{
    use StripeTrait;

    public function __construct()
    {
        $this->middleware('ajax.json')->only([
            'profile', 'sources', 'deleteSource',
            'setDefaultSource', 'addSource', 'editSource',
            'verifySource', 'image'
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $reservations = RsReservation::active();

        if ($user->is_customer) {
            $reservations = $reservations->where('customer_id', $user->id);
        } else if ($user->is_broker) {
            $reservations = $reservations->where('broker_id', $user->id);
        } else if ($user->is_owner) {
            /*$reservations = $reservations->joinLeft('rs_properties', 'owner_id', '=', 'rs_properties.id')
                ->where('owner_id', $user->id);*/

            /*$reservations = $reservations->join('rs_properties', 'rs_reservations.property_id', '=', 'rs_properties.id')
                ->where('rs_properties.owner_id', $user->id);*/

            $reservations = $reservations->join('rs_properties', function($join) use ($user){
                $join->on('rs_reservations.property_id','=','rs_properties.id')
                    ->where('rs_properties.owner_id','=',$user->id);
            });
        }

        $with = [
            'reservations_count' => $reservations->count(),
            'types' => CancellationType::TYPES,
            'statuses' => ReservationStatus::STATUSES
        ];

        if (!$user->is_broker) {
            $stripeAccount = GeneralHelper::createStripeStandardAccount($user, true);
            $userFeeConfig = RsUserFeeConfiguration::where('rs_user_id', $user->id)->first();

            $with['user_fee_config'] = $userFeeConfig;

            if($userFeeConfig == null || $userFeeConfig->stripe_account_completed == false){
                $stripeUrl = GeneralHelper::getStripeConnectUrl($user, route('stripe_reload'),route('stripe_reload'));
                $with['stripe_url'] = $stripeUrl;
            }
        }

        return view('profile')->with($with);
    }

    public function getUserFeeConfig(Request $request, $id = null)
    {
        $accountCompleted = false;
        $feeConfig = RsUserFeeConfiguration::where('rs_user_id', $id)->first();

        if($feeConfig){
            $accountCompleted = $feeConfig->stripe_account_completed;
        }

        $creditCards = RsCreditCard::where('rs_user_id', $id)
            ->where('status', 'active')
            ->where('verified', 1)->count();

        $userFeeConfig = [
            'stripe_account_completed' => $accountCompleted,
            'has_credit_card' => $creditCards
        ];

        return Response::json($userFeeConfig);
    }

    public function stripeReload(Request $request)
    {
        $user = $request->user();

        $userFeeConfig = RsUserFeeConfiguration::where('rs_user_id', $user->id)->first();

        if($userFeeConfig){
            if(!is_null_or_empty($userFeeConfig->stripe_account_id)){
                $stripeAccount = GeneralHelper::createStripeStandardAccount($user, true);

                if($stripeAccount->charges_enabled == true) {
                    $userFeeConfig->stripe_account_completed = true;
                    $userFeeConfig->save();
                }
            }
        }

        return redirect()->route('profile');
    }

    public function profile(EditProfile $request)
    {
        try {
            $changes = 0;
            $user = $request->user();

            if ($request->input('first_name') != $user->first_name) {
                $user->first_name = $request->input('first_name');
                $changes++;
            }

            if ($request->input('last_name') != $user->last_name) {
                $user->last_name = $request->input('last_name');
                $changes++;
            }

            if (!is_null($request->input('phone_number')) && $request->input('phone_number') != $user->phone_number) {
                $user->phone_number = $request->input('phone_number');
                $changes++;
            }

            if (!is_null($request->input('email')) && $request->input('email') != $user->email) {
                $user->email = $request->input('email');
                $changes++;
            }

            if (!is_null($request->input('pin')) && $request->input('pin') != $user->pin) {
                $user->pin = $request->input('pin');
                $changes++;
            }

            if (!is_null($request->input('password')) && $request->input('password') != $user->password) {
                $user->password = bcrypt($request->input('password'));
                $changes++;
            }

            if ($request->input('address_1') != $user->address_1) {
                $user->address_1 = $request->input('address_1');
                $changes++;
            }

            if ($request->input('house_number') != $user->house_number) {
                $user->house_number = $request->input('house_number');
                $changes++;
            }

            if ($request->input('address_2') != $user->address_2) {
                $user->address_2 = $request->input('address_2');
                $changes++;
            }

            if ($request->input('city') != $user->city) {
                $user->city = $request->input('city');
                $changes++;
            }

            if ($request->input('state') != $user->state) {
                $user->state = $request->input('state');
                $changes++;
            }

            if ($request->input('zipcode') != $user->zipcode) {
                $user->zipcode = $request->input('zipcode');
                $changes++;
            }

            if ($request->input('country') != $user->country) {
                $user->country = $request->input('country');
                $changes++;
            }

            if ($user->is_broker) {
                if (GeneralHelper::isNullOrEmpty($request->input('broker_cut'))) {
                    $user->broker_cut = 10.00;
                    $changes++;
                } else if ($request->input('broker_cut') != $user->broker_cut) {
                    $user->broker_cut = $request->input('broker_cut');
                    $changes++;
                }
            } else if ($user->broker_cut != 0) {
                $user->broker_cut = 0;
                $changes++;
            }

            if ($changes > 0) {
                GeneralHelper::stripeCustomerUpdate($user);
                $user->save();
                return $this->jsonSuccessResponse();
            } else {
                return $this->jsonNoChangeRequiredResponse();
            }
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.image") . '::' . $e->getMessage());
        }
    }

    public function image(Request $request)
    {
        try {
            if ($request->has('base_64')) {
                $user = $request->user();

                $base_64 = $request->input('base_64');
                $image_output = base64ToImage($base_64, '/images/profile/user_' . time());

                if (!GeneralHelper::isNullOrEmpty($user->profile_image) && $user->profile_image != '/images/profile/user.png' &&
                    file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                $user->profile_image = $image_output;
                $user->save();

                return $this->jsonSuccessResponse();
            }
            return $this->jsonErrorResponse(trans('property.image.not_found'));
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans('property.error.deleting') . '::' . $e->getMessage());
        }
    }

    public function sources($source = null, $user = null)
    {
        try {
            // Get source from route or default to 'card'
            $source = $source ?? 'card';
            
            $current_user = GeneralHelper::isNullOrEmpty($user) ? request()->user() : RsUser::active()->findOrFail($user);

            $customer = new Customer($current_user);
            $default_source = $customer->getDefaultSource();
            $sources = $customer->source($source)->all(100)->toArray();
            $sources['default_source'] = $default_source;

            return $this->jsonSuccessResponse($sources);
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.listing_sources") . '::' . $e->getMessage());
        }
    }

    public function deleteSource(Request $request)
    {
        try {
            $source = new Source($request->user(), $request->input('source'));

            $source->delete($request->input('source_id'));

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.deleting_sources") . '::' . $e->getMessage());
        }
    }

    public function setDefaultSource(Request $request)
    {
        try {
            $customer = new Customer($request->user());

            $customer->setDefaultSource($request->input('default_source'));

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.setting_sources") . '::' . $e->getMessage());
        }
    }

    public function addSource(AddSource $request)
    {
        try {
            $source_details = $request->all();

            if($request->input('source') == 'card') {
                $user = $request->user();

                $card = [
                    'number' => $source_details['number'],
                    'exp_month' => $source_details['exp_month'],
                    'exp_year' => $source_details['exp_year'],
                    'cvc' => $source_details['cvc'],
                ];

                $source = new Source($user, SourceType::CARD);
                $result = $source->create($card);
            } else {
                $source = new Source($request->user(), $request->input('source'));

                $source_details = $request->all();
                unset($source_details['source']);
                unset($source_details['account_number_confirmation']);

                $source->create($source_details);
            }

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.adding_sources") . '::' . $e->getMessage());
        }
    }

    /*
    public function addSource(AddSource $request)
    {
        try {
            $source_details = $request->all();

            if($request->input('source') == 'card') {
                $user = $request->user();

                $card = [
                    'number' => $source_details['number'],
                    'exp_month' => $source_details['exp_month'],
                    'exp_year' => $source_details['exp_year'],
                    'cvc' => $source_details['cvc'],
                ];

                $source = new Source($user, SourceType::CARD);
                $result = $source->create($card);

                //============================================
                //============================================
                //verify credit card
                try{
                    $description = 'House Rental property subscription';
                    $charge = self::doStripeCharge($user, 15, $description, $result['id']);
                    if ($charge != null) {
                        //create invoice

                        //create invoice payment

                        //send invoice notification
                        $notificationType = BsNotificationType::NONE;

                        if ($charge->status == BsChargeStatus::SUCCEEDED) {
                            //create invoice transaction

                            //save credit card source id
                            $cc = new RsCreditCard();
                            $cc->rs_user_id = $user->id;
                            $cc->stripe_credit_card_id = $result['id'];
                            $cc->name = $source_details['name'];//$result['name'];
                            $cc->last4 = $result['last4'];
                            $cc->brand = $result['brand'];
                            $cc->type = ucwords($result['funding']) . ' card';
                            $cc->exp_month = $result['exp_month'];
                            $cc->exp_year = $result['exp_year'];
                            $cc->verified = true;
                            $cc->save();

                            $notificationType = BsNotificationType::INVOICE_PAID;

                        } else if ($charge->status == BsChargeStatus::FAILED) {
                            //send fail notification
                            $notificationType = BsNotificationType::CARD_DECLINED;
                            return null;
                        }
                    }
                } catch (\Exception $e) {
                    GeneralHelper::userLog('RsApp web addSource', $user, $e);
                    return $this->jsonErrorResponse(trans("profile.errors.adding_sources") . '::' . $e->getMessage());
                }
                //============================================
                //============================================

            } else {
                $source = new Source($request->user(), $request->input('source'));

                $source_details = $request->all();
                unset($source_details['source']);
                unset($source_details['account_number_confirmation']);

                $source->create($source_details);
            }

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.adding_sources") . '::' . $e->getMessage());
        }
    }

    */

    public function editSource(EditSource $request)
    {
        try {
            $source = new Source($request->user(), $request->input('source'));

            $source_details = $request->all();
            unset($source_details['id']);
            unset($source_details['source']);

            $source->update($request->input('id'), $source_details);

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.editing_sources") . '::' . $e->getMessage());
        }
    }

    public function verifySource(Request $request)
    {
        try {
            if ($request->input('source') == SourceType::BANK_ACCOUNT) {
                $source = new Source($request->user(), $request->input('source'));

                $source->verify($request->input('source_id'));
            }

            return $this->jsonSuccessResponse();
        } catch (\Exception $e) {
            return $this->jsonErrorResponse(trans("profile.errors.verifying_sources") . '::' . $e->getMessage());
        }
    }

    //========================================
    public function creditCardPayment(Request $request)
    {
        //verify credit card
        try{
            $cc_info = $request->all();

            $accountNumber = $cc_info['number'];
            $expMonth = $cc_info['exp_month'];
            $expYear = $cc_info['exp_year'];
            $cvc = $cc_info['cvc'];

            $card = [
                'number' => $accountNumber,
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvc' => $cvc,
            ];

            $user = $request->user();
            $source_details = $card;
            $source = new Source($user, SourceType::CARD);
            $result = $source->create($source_details);

            $description = 'House Rental property subscription';
            $charge = self::doStripeCharge($user, env('SUBSCRIPTION_PRICE'), $description, $result['id']);
            if ($charge != null) {
                //create invoice

                //create invoice payment

                //send invoice notification
                $notificationType = BsNotificationType::NONE;

                if ($charge->status == BsChargeStatus::SUCCEEDED) {
                    //create invoice transaction

                    //save credit card source id
                    $cc = new RsCreditCard();
                    $cc->rs_user_id = $user->id;
                    $cc->stripe_credit_card_id = $result['id'];
                    $cc->name = ''; //$result['name'];
                    $cc->last4 = $result['last4'];
                    $cc->brand = $result['brand'];
                    $cc->type = ucwords($result['funding']) . ' card';
                    $cc->exp_month = $result['exp_month'];
                    $cc->exp_year = $result['exp_year'];
                    $cc->verified = true;
                    $cc->save();

                    $notificationType = BsNotificationType::INVOICE_PAID;

                } else if ($charge->status == BsChargeStatus::FAILED) {
                    //send fail notification
                    $notificationType = BsNotificationType::CARD_DECLINED;
                    return $this->jsonErrorResponse('Payment error::Charge failed or card declined.');
                }

                return $this->jsonSuccessResponse();
            }
            return $this->jsonErrorResponse('Payment error::Charge failed.');
        } catch (\Exception $e) {
            GeneralHelper::userLog('RsApp web creditCardPayment', $user, $e);
            return $this->jsonErrorResponse('Payment error::' . $e->getMessage());
        }
    }
}