<?php

namespace App\Notifications;

use App\Channels\Messages\TwilioSmsMessage;
use Illuminate\Notifications\Messages\MailMessage;
use SMD\Common\ReservationSystem\Enums\PaymentMethod;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use App\RsUser;

class RsReservationCanceled extends BaseNotification
{
    private $reservation;
    private $bookForYou;

    /**
     * RsReservationCanceled constructor.
     *
     * @param \SMD\Common\ReservationSystem\Models\RsReservation $reservation
     * @param bool $bookForYou
     */
    public function __construct($reservation, $bookForYou = true)
    {
        parent::__construct();

        $this->reservation = $reservation;
        $this->bookForYou = $bookForYou;
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
            ->error()
            ->subject(trans('notification.subject.reservation.' . ReservationStatus::CANCELED))
            ->greeting($this->greeting('email', GeneralHelper::getUserFullName($notifiable)));

        if ($notifiable->is_customer) {
            $message->line($this->cancellerLine());
        } else {
            $message->line($this->ownerBrokerLine($notifiable));
        }

        $message->action(trans('notification.actions.details'), route('reservation_details', [
            'id' => $this->reservation->id
        ]));

        if ($notifiable->is_customer) {
            $payment = $this->reservation->payment;
            if ($payment != null && $payment->method == PaymentMethod::CARD) {
                $line = str_replace([':brand', ':ended', ':amount'],
                    [strtoupper($payment->stripe_brand), $payment->stripe_last4, $this->reservation->total_price],
                    trans('notification.stripe.refund'));
                $message->line($line);
            }
        }

        return $message->salutation($this->salutation('email'));
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
            $this->cancellerLine(),
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
    private function ownerBrokerLine($notifiable)
    {
        $from = $notifiable->is_owner
            ? ($this->reservation->broker == null
                ? trans('notification.content.you')
                : GeneralHelper::getUserFullName($this->reservation->broker))
            : trans('notification.content.you');

        $to = GeneralHelper::getUserFullName($this->reservation->customer);

        return str_replace([':from', ':to'], [$to, $from],
                trans('notification.content.reservation_canceled.owner_broker')) .
            trans('notification.content.click_link');
    }

    private function cancellerLine()
    {
        $canceller = $this->bookForYou
            ? GeneralHelper::getUserFullName($this->reservation->broker == null ? $this->reservation->property->owner : $this->reservation->broker)
            : '';

        return str_replace([':full_name'], [$canceller],
                trans('notification.content.reservation_canceled.canceller' . ($this->bookForYou ? '_for_you' : ''))) .
            trans('notification.content.click_link');
    }
}