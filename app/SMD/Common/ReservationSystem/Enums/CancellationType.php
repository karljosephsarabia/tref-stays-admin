<?php

namespace SMD\Common\ReservationSystem\Enums;

class CancellationType
{
    const NONE = 'none';
    const FULL = 'full';
    const PARTIAL = 'partial';

    const TYPES = [
        self::NONE => 'None',
        self::FULL => 'Full Refund',
        self::PARTIAL => 'Partial Refund',
    ];
}
