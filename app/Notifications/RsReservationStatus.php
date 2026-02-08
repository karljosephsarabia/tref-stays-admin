<?php

namespace App\Notifications;

use App\Channels\Messages\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use App\RsUser;

class RsReservationStatus extends BaseNotification
{
    private $reservation;
    private $status;

    /**
     * RsReservationStatus constructor.
     *
     * @param \SMD\Common\ReservationSystem\Models\RsReservation $reservation
     * @param string $status
     */
    public function __construct($reservation, $status)
    {
        parent::__construct();
        $this->reservation = $reservation;
        $this->status = $status;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param RsUser $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(trans('notification.subject.reservation.' . $this->status))
            ->greeting($this->greeting('email', GeneralHelper::getUserFullName($notifiable)));

        $message->line($this->introLine($notifiable));

        $message->action(trans('notification.actions.details'), route('reservation_details', [
            'id' => $this->reservation->id
        ]));

        return $message
            ->line($this->outroLine($notifiable))
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
            $this->introLine($notifiable),
            /*route('reservation_details', [
                'id' => $this->reservation->id
            ])*/
        ];

        return (new TwilioSmsMessage)
            ->content(join(' ', $content));
    }

    /**
     * @param RsUser $notifiable
     * @return string
     */
    private function introLine($notifiable)
    {
        return ($notifiable->is_customer
                ? trans('notification.content.reservation_status.your_reservation')
                : str_replace([':full_name'], [GeneralHelper::getUserFullName($this->reservation->customer)],
                    trans('notification.content.reservation_status.the_reservation')))
            . trans('notification.content.reservation_status.masked_as')
            . trans('notification.content.reservation_status.' . $this->status)
            . trans('notification.content.click_link');
    }

    /**
     * @param RsUser $notifiable
     * @return string
     */
    private function outroLine($notifiable)
    {
        return '';
    }
}