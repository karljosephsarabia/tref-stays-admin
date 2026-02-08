<?php

namespace SMD\Common\ReservationSystem\Enums;

class ReservationStatus
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const CANCELLED = 'cancelled';
    const COMPLETED = 'completed';
    const CHECKED_OUT = 'checked_out';
    const CHECKED_IN = 'checked_in';
    const CANCELED = 'canceled';
    const EXPIRED = 'expired';
    const ACTIVE = 'active';

    const STATUSES = [
        self::PENDING => 'Pending',
        self::CONFIRMED => 'Confirmed',
        self::CANCELLED => 'Cancelled',
        self::COMPLETED => 'Completed',
        self::CHECKED_OUT => 'Checked Out',
        self::CHECKED_IN => 'Checked In',
        self::CANCELED => 'Canceled',
        self::EXPIRED => 'Expired',
        self::ACTIVE => 'Active',
    ];
}
