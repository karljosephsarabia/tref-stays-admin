<?php

namespace SMD\Common\ReservationSystem\Enums;

class RsPaymentVia
{
    const CARD = 'card';
    const BANK = 'bank';
    const CASH = 'cash';
    const CHECK = 'check';
    const TRANSFER = 'transfer';

    const TYPES = [
        self::CARD => 'Credit Card',
        self::BANK => 'Bank Transfer',
        self::CASH => 'Cash',
        self::CHECK => 'Check',
        self::TRANSFER => 'Transfer',
    ];
}
