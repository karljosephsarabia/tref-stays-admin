<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SMD\Common\ReservationSystem\Enums\NotificationType;
use SMD\Common\ReservationSystem\Enums\ReservationActivityType;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;
use SMD\Common\ReservationSystem\Models\RsReservation;
use SMD\Common\ReservationSystem\Models\RsReservationActivity;

class CheckReservationStatus extends Command
{
    /**
     * @var string
     */
    protected $signature = 'rs:check_reservation_status';

    /**
     * @var string
     */
    protected $description = 'Check change Reservation status and activity based on checkin date';

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
            $date = new \DateTime();
            $today = $date->format('Y-m-d');

            $this->updateTodayReservations($today);
            $this->updateExpiredReservations($today);

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
     * @param string $today
     */
    private function updateExpiredReservations($today)
    {
        $reservations = RsReservation::active()
            ->where('date_start', '<', $today)
            ->get();

        foreach ($reservations as $reservation) {
            DB::beginTransaction();
            try {
                $reservation->status = ReservationStatus::EXPIRED;
                $reservation->save();
                $this->setReservationActivity($reservation->id, ReservationActivityType::CUSTOMER_NEVER_ARRIVED);
                DB::commit();
                try {
                    $type = NotificationType::RESERVATION_STATUS;
                    GeneralHelper::userNotification($reservation->customer, $type, $reservation);
                    GeneralHelper::userNotification($reservation->property->owner, $type, $reservation);
                } catch (\Exception $ex) {
                    GeneralHelper::userLog('notification[reservation-status]', null, $ex);
                }
                $this->info('Reservation activity updated successfully.');
            } catch (\Exception $ex) {
                DB::rollback();
                GeneralHelper::userLog('rs:check_reservation_status', null, $ex);
                $this->error('Error updating activity for expired reservations :: ' . $ex->getMessage());
            }
        }
    }

    /**
     * @param string $today
     */
    private function updateTodayReservations($today)
    {
        $reservations = RsReservation::active()
            ->whereDateStart($today)
            ->withCount(['activities' => function ($query) {
                $query->whereActivity(ReservationActivityType::WAITING_CUSTOMER);
            }])
            ->having('activities_count', 0)
            ->get();

        foreach ($reservations as $reservation) {
            try {
                $this->setReservationActivity($reservation->id, ReservationActivityType::WAITING_CUSTOMER);
                DB::commit();
                $this->info('Reservation activity updated successfully.');
            } catch (\Exception $ex) {
                DB::rollback();
                GeneralHelper::userLog('rs:check_reservation_status', null, $ex);
                $this->error('Error updating activity for today\'s reservations :: ' . $ex->getMessage());
            }
        }
    }

    /**
     * @param int $reservation_id
     * @param string $type
     */
    private function setReservationActivity($reservation_id, $type)
    {
        $activity = new RsReservationActivity();
        $activity->activity = $type;
        $activity->reservation_id = $reservation_id;
        $activity->save();
    }
}