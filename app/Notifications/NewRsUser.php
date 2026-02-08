<?php

namespace App\Notifications;

use App\Channels\Messages\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use App\RsUser;

class NewRsUser extends BaseNotification
{
    private $forYou;

    public function __construct($forYou = true)
    {
        parent::__construct();

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
            ->subject(trans('notification.subject.user.new'))
            ->greeting($this->greeting('email', GeneralHelper::getUserFullName($notifiable)))
            ->line($this->introLine())
            ->action(trans('notification.actions.go'), route('home'))
            ->line(str_replace([':email'], [$notifiable->email], trans('notification.content.new_user.login')))
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
            $this->introLine(),
            //route('home')
        ];

        return (new TwilioSmsMessage)
            ->content(join(' ', $content));
    }

    private function introLine()
    {
        return trans('notification.content.new_user.intro' . (!$this->forYou ? '' : '_for_you')) .
            trans('notification.content.click_link');
    }

    private function outroLine()
    {
        return trans('notification.content.new_user.outro' . (!$this->forYou ? '' : '_for_you'));
    }
}