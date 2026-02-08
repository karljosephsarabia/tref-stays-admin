<?php

namespace SMD\Common\Stripe\Enums;

class AccountHolderType
{
    const INDIVIDUAL = 'individual';
    const COMPANY = 'company';

    const TYPES = [
        self::INDIVIDUAL => 'Individual',
        self::COMPANY => 'Company',
    ];
}
