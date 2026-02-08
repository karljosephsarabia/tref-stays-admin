<?php

namespace SMD\Common\ReservationSystem\Enums;

class PaymentMethod
{
    const CARD = 'card';
    const BANK = 'bank';
    const CASH = 'cash';

    const TYPES = [
        self::CARD => 'Credit Card',
        self::BANK => 'Bank Transfer',
        self::CASH => 'Cash',
    ];
}
