<?php

namespace SMD\Common\ReservationSystem\Enums;

class NotificationType
{
    const EMAIL = 'email';
    const SMS = 'sms';
    const PUSH = 'push';

    // Notification event types
    const USER_CREATED = 'user_created';
    const USER_DISABLED = 'user_disabled';
    const RESERVATION_CREATED = 'reservation_created';
    const RESERVATION_CANCELED = 'reservation_canceled';
    const RESERVATION_STATUS = 'reservation_status';
    const PROPERTY_POSTED = 'property_posted';
    const CREDIT_CARD = 'credit_card';

    const TYPES = [
        self::EMAIL => 'Email',
        self::SMS => 'SMS',
        self::PUSH => 'Push Notification',
    ];
}
