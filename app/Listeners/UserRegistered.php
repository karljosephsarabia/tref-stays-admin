<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Enums\NotificationType;

class UserRegistered
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;
        try {
            GeneralHelper::userNotification($event->user, NotificationType::USER_CREATED);
        } catch (\Exception $ex) {
            GeneralHelper::userLog('notification[new-user]', $user, $ex);
        }
    }
}