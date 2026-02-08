<?php

namespace SMD\Common\ReservationSystem\Enums;

class ReservationActivityType
{
    const BROKER_CREATED = 'broker_created';
    const OWNER_CREATED = 'owner_created';
    const CUSTOMER_CREATED = 'customer_created';
    const BROKER_CANCELLED = 'broker_cancelled';
    const OWNER_CANCELLED = 'owner_cancelled';
    const CUSTOMER_CANCELLED = 'customer_cancelled';
    const CUSTOMER_LEFT = 'customer_left';
    const CUSTOMER_ARRIVED = 'customer_arrived';
    const CUSTOMER_NEVER_ARRIVED = 'customer_never_arrived';
    const WAITING_CUSTOMER = 'waiting_customer';
}
