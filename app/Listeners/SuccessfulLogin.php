<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;

class SuccessfulLogin
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Login $event)
    {
        try {
            $user = $event->user;
            if (GeneralHelper::isNullOrEmpty($user->stripe_customer_id)) {
                GeneralHelper::stripeCustomerUpdate($user);
                $user->save();
            }
            GeneralHelper::userLog('login', $user);
        } catch (\Exception $e) {
            \Log::debug('SuccessfulLogin handle error :: ' + json_encode($e));
        }
    }
}
