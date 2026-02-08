<?php

namespace App\Notifications;

use App\Channels\Messages\TwilioSmsMessage;
use App\RsUser;
use Illuminate\Notifications\Messages\MailMessage;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;

class RsCreditCard extends BaseNotification
{
    private $notification;
    private $forYou;

    public function __construct($notification, $forYou = true)
    {
        parent::__construct();

        $this->notification = $notification;
        $this->forYou = $forYou;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param RsUser $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('House Rental: Credit Card')
            ->greeting($this->greeting('email', GeneralHelper::getUserFullName($notifiable)))
            ->line($this->notification->custom_message)
            ->action(trans('notification.actions.go'), route('home'))
            ->line(str_replace([':email'], [$notifiable->email], trans('notification.content.new_user.login')))
            ->salutation($this->salutation('email'));
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param RsUser $notifiable
     * @return TwilioSmsMessage
     */
    public function toTwilioSms($notifiable)
    {
        $content = [
            $this->notification->custom_message,
            //route('home')
        ];

        return (new TwilioSmsMessage)
            ->content(join(' ', $content));
    }
}