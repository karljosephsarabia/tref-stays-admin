<?php

namespace SMD\Common\Stripe\Enums;

class SourceType
{
    const CARD = 'card';
    const BANK_ACCOUNT = 'bank_account';

    const TYPES = [
        self::CARD => 'Credit Card',
        self::BANK_ACCOUNT => 'Bank Account',
    ];
}
