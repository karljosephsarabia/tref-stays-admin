<?php

namespace SMD\Common\ReservationSystem\Enums;

class RoleType
{
    const ADMIN = 'admin';
    const BROKER = 'broker';
    const OWNER = 'owner';
    const RENTER = 'renter';
    const GUEST = 'guest';
    const CUSTOMER = 'customer';
    
    // Array of roles that can register
    const REGISTER = [
        self::OWNER => 'Owner',
        self::CUSTOMER => 'Customer',
        self::RENTER => 'Renter',
    ];

    // Array of all roles
    const ALL = [
        self::ADMIN => 'Admin',
        self::BROKER => 'Broker',
        self::OWNER => 'Owner',
        self::RENTER => 'Renter',
        self::CUSTOMER => 'Customer',
        self::GUEST => 'Guest',
    ];

    // Role types as array
    const TYPES = [
        self::ADMIN => 'Admin',
        self::BROKER => 'Broker',
        self::OWNER => 'Owner',
        self::RENTER => 'Renter',
        self::CUSTOMER => 'Customer',
        self::GUEST => 'Guest',
    ];
}
