<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;

class SuccessfulLogout
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Logout $event
     * @return void
     */
    public function handle(Logout $event)
    {
        try {
            $user = $event->user;
            if (GeneralHelper::isNullOrEmpty($user->stripe_customer_id)) {
                GeneralHelper::stripeCustomerUpdate($user);
            }
            GeneralHelper::userLog('logout', $user);
        } catch (\Exception $e) {
            \Log::debug('SuccessfulLogout handle error :: ' + json_encode($e));
        }
    }
}
