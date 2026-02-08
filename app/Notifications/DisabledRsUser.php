<?php

namespace App\Notifications;

use App\Channels\Messages\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use App\RsUser;

class DisabledRsUser extends BaseNotification
{
    private $forPayment;

    public function __construct($forPayment = false)
    {
        parent::__construct();

        $this->forPayment = $forPayment;
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
            ->subject(trans('notification.subject.user.disabled' . (!$this->forPayment ? '' : '_payment')))
            ->greeting($this->greeting('email', GeneralHelper::getUserFullName($notifiable)))
            ->line($this->introLine())
            ->line($this->outroLine())
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
            $this->greeting('sms', config('app.name')),
            $this->introLine()
        ];

        return (new TwilioSmsMessage)
            ->content(join(' ', $content));
    }

    private function introLine()
    {
        return trans('notification.content.disabled.intro' . (!$this->forPayment ? '' : '_payment'));
    }

    private function outroLine()
    {
        return str_replace([':email'], [$this->systemEmail()],
            trans('notification.content.disabled.outro' . (!$this->forPayment ? '' : '_payment')));
    }
}