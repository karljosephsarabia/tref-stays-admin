<?php

namespace App\Notifications;

use App\Channels\TwilioSmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use SMD\Common\ReservationSystem\Enums\NotificationType;
use App\RsUser;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $type;

    public function __construct($type = NotificationType::NONE)
    {
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param RsUser $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = [];

        if (!GeneralHelper::isNullOrEmpty($notifiable->email)) {
            $via[] = 'mail';
        }

        if (!GeneralHelper::isNullOrEmpty($notifiable->phone_number)) {
            $via[] = TwilioSmsChannel::class;
        }

        return $via;
    }

    public function greeting($type, $name)
    {
        return str_replace([':full_name'], [$name], trans('notification.greeting.' . $type));
    }

    public function salutation($type)
    {
        return trans('notification.salutation.' . $type);
    }

    public function systemEmail()
    {
        return RsUser::brokers()->first()->email;
    }
}