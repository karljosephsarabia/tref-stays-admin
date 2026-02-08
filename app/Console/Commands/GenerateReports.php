<?php

namespace App\Console\Commands;

use App\RsUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;
use SMD\Common\ReservationSystem\Helpers\GeneralHelper;

class GenerateReports extends Command
{
    /**
     * @var string
     */
    protected $signature = 'rs:generate_reports';

    /**
     * @var string
     */
    protected $description = 'Generate owners incoming reports';

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
            $this->incomingReports();
        } else {
            $this->info('Looks like another instance running... Will wait!');
            return 1;
        }
    }

    private function incomingReports()
    {
        $users = RsUser::owners()->get();
        $start_date = new Carbon('first day of last month');
        $end_date = new Carbon('last day of last month');

        $start_date = $start_date->startOfMonth();
        $end_date = $end_date->endOfMonth();

        $this->info('Loading owners...');

        foreach ($users as $user) {
            if (!$user->incomingReports()->where('starting_at', $start_date)->where('ending_at', $end_date)->exists()) {
                $this->info('Creating incoming report...');
                try {
                    $income = create_incoming_record($user, $start_date, $end_date);
                    if ($income->ending_balance == 0) {
                        $income->paid_at = Carbon::now();
                        $income->payment_via = RsPaymentVia::CASH;
                        $income->payment_done = true;
                    }
                    $income->save();
                    $this->info('Incoming report created...');
                } catch (\Exception $ex) {
                    $this->error('An error occurred while trying to send notification: ' . GeneralHelper::getExceptionJson($ex));
                }
            }
        }
    }
}