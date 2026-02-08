<?php

namespace App\Console\Commands;

use App\Notifications\DisabledRsUser;
use App\Notifications\NewRsReservation;
use App\Notifications\NewRsUser;
use App\Notifications\PropertyPosted;
use App\Notifications\RsCreditCard;
use App\Notifications\RsReservationCanceled;
use App\Notifications\RsReservationStatus;
use App\RsUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use SMD\Common\ReservationSystem\Enums\NotificationType;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsUserNotification;

class SendNotification extends Command
{
    /**
     * @var string
     */
    protected $signature = 'rs:send_notifications';

    /**
     * @var string
     */
    protected $description = 'Send user notifications from the reservation system';

    /**
     * CheckReservationStatus constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sem_key = 9578196;
        $sem_max_acquire = 1;
        $sem_permission = 0666;
        $sem_auto_release = 1;

        $sem = sem_get($sem_key, $sem_max_acquire, $sem_permission, $sem_auto_release);

        if (!$sem) {
            $this->info('An error occurred while creating lock');
            return 1;
        }

        if (sem_acquire($sem, true)) {
            $this->info('Loading notifications to be sent...');

            $notifications = RsUserNotification::where('sent', false)
                ->where('retries', '<', config('app.max_notification_retries', 3))
                ->orderByDesc('created_at')
                ->get();

            foreach ($notifications as $notification) {
                if(is_null_or_empty($notification->user->email)){
                    continue; //TODO: Check if will be used a default email
                }
                try {
                    if ($this->sendNotification($notification)) {
                        $notification->sent = true;
                        $notification->sent_at = Carbon::now();
                        $this->info('Sent');
                    } else {
                        $notification->retries++;
                    }
                } catch (\Exception $ex) {
                    $notification->retries++;
                    $this->error('An error occurred while trying to send notification: ' . $ex->getMessage());
                }
                $notification->save();
            }

            if (sem_release($sem)) {
                if (sem_remove($sem)) {
                    $this->info('Good bye!');
                    return 0;
                } else {
                    $this->error('An error occurred while removing lock');
                    return 1;
                }
            } else {
                $this->error('An error occurred while releasing lock');
                return 1;
            }
        } else {
            $this->info('Looks like another instance running... Will wait!');
            return 1;
        }
    }

    /**
     * @param RsUserNotification $notification
     * @return bool
     */
    private function sendNotification($notification)
    {
        $user = RsUser::fromInstance($notification->user);
        $this->info("Will trying to send to: " . GeneralHelper::getUserFullName($user));

        try {
            switch ($notification->type) {
                case NotificationType::USER_DISABLED_PAYMENT:
                    $user->notify(new DisabledRsUser(true));
                    break;
                case NotificationType::USER_DISABLED:
                    $user->notify(new DisabledRsUser(false));
                    break;

                case NotificationType::RESERVATION_CREATED_FOR_YOU:
                    $user->notify(new NewRsReservation($notification->reservation, true));
                    break;
                case NotificationType::RESERVATION_CREATED:
                    $user->notify(new NewRsReservation($notification->reservation, false));
                    break;

                case NotificationType::RESERVATION_CANCELLED_FOR_YOU:
                    $user->notify(new RsReservationCanceled($notification->reservation, true));
                    break;
                case NotificationType::RESERVATION_CANCELLED:
                    $user->notify(new RsReservationCanceled($notification->reservation, false));
                    break;

                case NotificationType::USER_CREATED_FOR_YOU:
                    $user->notify(new NewRsUser(true));
                    break;
                case NotificationType::USER_CREATED:
                    $user->notify(new NewRsUser(false));
                    break;

                case NotificationType::RESERVATION_CHECKED_IN:
                    $user->notify(new RsReservationStatus($notification->reservation, ReservationStatus::CHECKED_IN));
                    break;

                case NotificationType::RESERVATION_CHECKED_OUT:
                    $user->notify(new RsReservationStatus($notification->reservation, ReservationStatus::CHECKED_OUT));
                    break;

                case NotificationType::RESERVATION_EXPIRED:
                    $user->notify(new RsReservationStatus($notification->reservation, ReservationStatus::EXPIRED));
                    break;

                case NotificationType::PROPERTY_CREATED:
                    $user->notify(new PropertyPosted($notification));
                    break;

                case NotificationType::CREDIT_CARD_CREATED:
                case NotificationType::CREDIT_CARD_CHARGED:
                case NotificationType::CREDIT_CARD_CHARGE_FAILED:
                    $user->notify(new RsCreditCard($notification));
                    break;
            }
            return true;
        } catch (\Exception $ex) {
            $this->error('An error occurred while trying to send notification: ' . GeneralHelper::getExceptionJson($ex));//->getMessage());
            return false;
        }
    }
}
