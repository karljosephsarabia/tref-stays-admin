<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use SMD\Common\ReservationSystem\Enums\TransactionType;
use SMD\Common\ReservationSystem\Models\RsUser as User;

class RsUser extends User implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    /**
     * @param User $user
     * @return RsUser
     */
    public static function fromInstance($user)
    {
        $obj = new self();
        foreach (get_object_vars($user) as $key => $name) {
            $obj->$key = $name;
        }
        return $obj;
    }

    /**
     * @param $start string
     * @param $end string
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder|null
     */
    public function incoming($start, $end)
    {
        $sql = 'ifnull(sum(case when `type` = ? then `amount` else 0 end), 0.00) as `reservation_total`,'
            . 'ifnull(sum(case when `type` = ? then `amount` else 0 end) * -1, 0.00) as `commission_total`,'
            . 'ifnull(sum(case when `type` = ? then `amount` else 0 end) * -1, 0.00) as `cancellation_fee_total`,'
            . 'ifnull(sum(case when `type` = ? then `amount` else 0 end) * -1, 0.00) as `refund_total`,'
            . 'ifnull(sum(case when `type` = ? then `amount` else 0 end) * -1, 0.00) as `broker_fee_total`,'
            . 'ifnull(sum(case when `type` = ? then `amount` else 0 end) * -1, 0.00) as `payment_total`';

        return $this->transactions()
            ->selectRaw($sql, [
                TransactionType::RESERVATION, TransactionType::COMMISSION, TransactionType::CANCELLATION_FEE,
                TransactionType::REFUND, TransactionType::BROKER_FEE, TransactionType::PAYMENT
            ])
            ->where('active', true)
            ->whereBetween('created_at', [$start, $end])
            ->first();
    }

    public function currentIncomes()
    {
        $date = new Carbon('first day of this month');
        $starting_date = $date->startOfDay();

        $date = new Carbon('today');
        $ending_date = $date->endOfDay();

        return create_incoming_record($this, $starting_date, $ending_date);
    }

    public function incomingReports()
    {
        return parent::incomingReports()->orderByDesc('id');
    }
}
