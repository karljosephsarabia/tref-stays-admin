<?php

namespace SMD\Common\ReservationSystem\Enums;

class PropertyOption
{
    const WIFI = 'wifi';
    const PARKING = 'parking';
    const POOL = 'pool';
    const GYM = 'gym';
    const AC = 'ac';
    
    // Payment/Booking options
    const BY_CASH = 'by_cash';
    const BY_SYSTEM = 'by_system';
    const BY_CREDIT = 'by_credit';
    const CONNECT_OWNER = 'connect_owner';
    const CONNECT_BROKER = 'connect_broker';
    
    // Option groups for different property configurations
    const CASH_SYSTEM_OWNER = [
        self::BY_CASH,
        self::BY_SYSTEM,
        self::CONNECT_OWNER,
    ];
    
    const CASH_ONLY_OR_OWNER = [
        self::BY_CASH,
        self::CONNECT_OWNER,
    ];
    
    const CASH_ONLY = [
        self::BY_CASH,
    ];
    
    const SYSTEM_ONLY = [
        self::BY_SYSTEM,
    ];
    
    const CONNECT_OWNER_ONLY = [
        self::CONNECT_OWNER,
    ];
    
    const OWNER_OR_BROKER = [
        self::CONNECT_OWNER,
        self::CONNECT_BROKER,
    ];
    
    const BROKER_ONLY = [
        self::CONNECT_BROKER,
    ];
    
    // All option groups for property form
    const OPTION_GROUPS = [
        'CASH_SYSTEM_OWNER' => 'Cash, System & Owner',
        'CASH_ONLY_OR_OWNER' => 'Cash Only or Owner',
        'CASH_ONLY' => 'Cash Only',
        'SYSTEM_ONLY' => 'System Only',
        'CONNECT_OWNER_ONLY' => 'Connect Owner Only',
        'OWNER_OR_BROKER' => 'Owner or Broker',
        'BROKER_ONLY' => 'Broker Only',
    ];
}
