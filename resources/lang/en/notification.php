<?php

use SMD\Common\ReservationSystem\Enums\ReservationStatus;

return [
    'subject' => [
        'user' => [
            'new' => 'New Account',
            'disabled' => 'Account Disabled',
            'disabled_payment' => 'Account Disabled Due To Payment'
        ],
        'reservation' => [
            ReservationStatus::ACTIVE => 'New Reservation',
            ReservationStatus::CANCELED => 'Reservation Canceled',
            ReservationStatus::EXPIRED => 'Reservation Expired',
            ReservationStatus::CHECKED_IN => 'Reservation Checked In',
            ReservationStatus::CHECKED_OUT => 'Reservation Checked Out'
        ],
    ],
    'greeting' => [
        'email' => 'Hi :full_name,',
        'sms' => ':full_name:'
    ],
    'actions' => [
        'details' => 'View details',
        'go' => 'Go ahead'
    ],
    'content' => [
        'you' => 'You',
        'new_reservation' => [
            'booker' => 'Your reservation have been booked successfully',
            'booker_for_you' => ':full_name has booked a reservation for you',
            'owner_broker' => ':from booked a reservation for :to',
            'confirmation_number' => 'The confirmation number is: :number',
            'you_own' => 'You own :full_name $ :amount USD',
            'you_can_pay' => 'You can pay upon arrival at the property.'
        ],
        'reservation_canceled' => [
            'owner_broker' => ':from canceled a reservation for :to',
            'canceller' => 'Your reservation have been canceled successfully',
            'canceller_for_you' => ':full_name has canceled your reservation'
        ],
        'reservation_status' => [
            ReservationStatus::EXPIRED => 'Expired',
            ReservationStatus::CHECKED_IN => 'Checked In',
            ReservationStatus::CHECKED_OUT => 'Checked Out',
            'masked_as' => ' was marked as ',
            'your_reservation' => 'Your reservation',
            'the_reservation' => 'The reservation for :full_name'
        ],
        'disabled' => [
            'intro' => 'Your account have been disabled.',
            'intro_payment' => 'Your account have been disabled due to missing payment.',
            'outro' => 'Please, email us to :email for more details.',
            'outro_payment' => 'Please, email us to :email for more details.',
        ],
        'new_user' => [
            'login' => 'You can login anytime by using this email: :email',
            'intro' => 'Your account have been created successfully',
            'intro_for_you' => 'A new account have been created for you',
            'outro' => '',
            'outro_for_you' => 'Don\'t forget to change your password once you are logged in.'
        ],
        'click_link' => ', click the link below for accessing:'
    ],
    'salutation' => [
        'email' => 'Thank you for using our application!',
        'sms' => ''
    ],
    'stripe' => [
        'charge' => 'Your card :brand ended in :ended was charge for $ :amount USD',
        'refund' => 'Your card :brand ended in :ended received a $ :amount USD refund.'
    ]
];