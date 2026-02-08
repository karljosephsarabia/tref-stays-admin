<?php

namespace SMD\Common\ReservationSystem\Enums;

class TransactionType
{
    const PAYMENT = 'payment';
    const REFUND = 'refund';
    const TRANSFER = 'transfer';
    const PAYOUT = 'payout';
    const RESERVATION = 'reservation';
    const COMMISSION = 'commission';
    const CANCELLATION_FEE = 'cancellation_fee';
    const BROKER_FEE = 'broker_fee';

    const TYPES = [
        self::PAYMENT => 'Payment',
        self::REFUND => 'Refund',
        self::TRANSFER => 'Transfer',
        self::PAYOUT => 'Payout',
        self::RESERVATION => 'Reservation',
        self::COMMISSION => 'Commission',
        self::CANCELLATION_FEE => 'Cancellation Fee',
        self::BROKER_FEE => 'Broker Fee',
    ];
}
