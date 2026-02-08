<?php

use SMD\Common\ReservationSystem\Enums\CancellationType;
use SMD\Common\ReservationSystem\Enums\PaymentMethod;
use SMD\Common\ReservationSystem\Enums\ReservationActivityType;
use SMD\Common\ReservationSystem\Enums\ReservationStatus;
use SMD\Common\ReservationSystem\Enums\RoleType;

return [
    'status_active' => 'Active',
    'status_done' => 'Done',
    'status_cancelled' => 'Cancelled',
    'show_title' => 'Previous Reservations',
    'add_reservation_title' => 'Add new reservation',
    'customer' => 'Customer',
    'property' => 'Property',
    'broker' => 'Broker',
    'date_start' => 'Start at',
    'date_end' => 'End at',
    'check_in' => 'Check in',
    'check_out' => 'Check out',
    'payment_methods' => 'Payment Methods',
    'charges' => 'Charges',
    'payment_method' => 'Payment Method',
    'payment_last4' => 'Last 4',
    'payment_brand' => 'Brand',
    'method' => [
        PaymentMethod::CARD => 'Card',
        PaymentMethod::CASH => 'Cash'
    ],
    'btn' => [
        'reserve' => 'Reserve',
        'reserved' => 'Reserved',
        'you_reserved' => 'You reserved',
        'payment' => 'Pay',
        'proceed' => 'Proceed',
        'no_availability' => 'No availability',
        'can_cancel' => 'You can Cancel',
        'cancel' => 'Cancel/Refund'
    ],
    'guests' => 'Guests',
    'bed_count' => 'Beds',
    'add_dates' => 'Add Dates',
    'add_guests' => 'Add Guests',
    'add_bed_count' => 'Add Beds',
    'per_night' => 'per night',
    'type_place' => ':type in :place',
    'cancellation_policy' => 'Cancellation policy',
    'additional_info' => 'Additional Information',
    'total' => 'Total',
    'service_fee' => 'Service Fee',
    'location' => 'Location',
    'processing_payment' => 'Processing payment.',
    'see_details' => 'See reservation details',
    'details' => [
        'price' => '$:amount',
        'price_nights' => ':price x :night nights',
        'guests' => ':guests guests',
        'bedrooms' => ':bedrooms bedrooms',
        'beds' => ':beds beds',
        'baths' => ':baths baths'
    ],
    'maximum_guest_count' => ':guest_count guests maximum. Infants don’t count toward the number of guests.',
    'cancellation' => [
        CancellationType::FULL => 'Full Price',
        CancellationType::NONE => 'No charges',
        CancellationType::PARTIAL => ':amount'
    ],
    'policies' => [
        CancellationType::FULL => 'The :cancellation_type paid will be retained due to cancellation.',
        CancellationType::NONE => 'You can cancel this reservation anytime. :cancellation_type will apply due to cancellation.',
        CancellationType::PARTIAL => '$ :cancellation_type will be retained due to cancellation.'
    ],
    'status' => [
        ReservationStatus::CHECKED_OUT => 'Checked out',
        ReservationStatus::CHECKED_IN => 'Checked in',
        ReservationStatus::CANCELED => 'Canceled',
        ReservationStatus::EXPIRED => 'Expired',
        ReservationStatus::ACTIVE => 'Active',
    ],
    'successful' => [
        ReservationStatus::CANCELED => 'Reservation canceled successfully',
        ReservationStatus::CHECKED_OUT => 'Reservation was checked out successfully',
        ReservationStatus::CHECKED_IN => 'Reservation was checked in successfully',
        ReservationStatus::EXPIRED => 'Reservation was marked expired successfully'
    ],
    'activity' => [
        RoleType::CUSTOMER => [
            ReservationActivityType::BROKER_CREATED => 'The broker booked it for you',
            ReservationActivityType::OWNER_CREATED => 'The owner booked it for you',
            ReservationActivityType::CUSTOMER_CREATED => 'You booked it',
            ReservationActivityType::BROKER_CANCELLED => 'The broker cancelled this reservation',
            ReservationActivityType::OWNER_CANCELLED => 'The owner cancelled this reservation',
            ReservationActivityType::CUSTOMER_CANCELLED => 'You cancelled this reservation',
            ReservationActivityType::CUSTOMER_LEFT => 'You left the property',
            ReservationActivityType::CUSTOMER_ARRIVED => 'You arrived to the property',
            ReservationActivityType::CUSTOMER_NEVER_ARRIVED => 'You never arrived',
            ReservationActivityType::WAITING_CUSTOMER => 'The owner is waiting for you'
        ],
        RoleType::BROKER => [
            ReservationActivityType::BROKER_CREATED => 'You booked it for the customer',
            ReservationActivityType::OWNER_CREATED => 'The owner booked it for the customer',
            ReservationActivityType::CUSTOMER_CREATED => 'The customer booked it',
            ReservationActivityType::BROKER_CANCELLED => 'You cancelled this reservation',
            ReservationActivityType::OWNER_CANCELLED => 'The owner cancelled this reservation',
            ReservationActivityType::CUSTOMER_CANCELLED => 'The customer cancelled this reservation',
            ReservationActivityType::CUSTOMER_LEFT => 'The customer left the property',
            ReservationActivityType::CUSTOMER_ARRIVED => 'The customer arrived to the property',
            ReservationActivityType::CUSTOMER_NEVER_ARRIVED => 'The customer never arrived',
            ReservationActivityType::WAITING_CUSTOMER => 'The owner is waiting for the customer'
        ],
        RoleType::OWNER => [
            ReservationActivityType::BROKER_CREATED => 'The broker booked it this reservation',
            ReservationActivityType::OWNER_CREATED => 'You booked it for the customer',
            ReservationActivityType::CUSTOMER_CREATED => 'The customer booked it',
            ReservationActivityType::BROKER_CANCELLED => 'The broker cancelled this reservation',
            ReservationActivityType::OWNER_CANCELLED => 'You cancelled this reservation',
            ReservationActivityType::CUSTOMER_CANCELLED => 'The customer cancelled this reservation',
            ReservationActivityType::CUSTOMER_LEFT => 'The customer checked out',
            ReservationActivityType::CUSTOMER_ARRIVED => 'The customer checked in',
            ReservationActivityType::CUSTOMER_NEVER_ARRIVED => 'The customer never checked in',
            ReservationActivityType::WAITING_CUSTOMER => 'You are waiting for the Customer'
        ]
    ],
    'range_invalid' => 'The selected range is not available',
    'no_cards_found' => 'No payment methods found',
    'no_card_selected' => 'No payment method selected',
    'pay_cash' => 'Pay using cash',
    'payment_description' => 'Property ":property" reserved by ":user" for :night nights',
    'error' => [
        'reserving' => 'Error while reserving this property',
        'paying' => 'An error occurred while processing the payment',
        'cancelling' => 'Error while cancelling this reservation',
        'refunding' => 'An error occurred while processing the refund',
        'no_active' => 'This reservation is not longer active',
        'changing_status' => 'Error while changes reservation status'
    ],
    'refund' => [
        'total' => 'Total Refund',
        'details' => 'Refund Details',
        'cancellation_fee' => 'Cancellation fee'
    ],
    'cancel_title' => 'Cancellation and Refund',
    'cancelling_reservation' => 'Cancelling Reservation.',
    'questions' => [
        'why_cancel' => 'Why is this reservation going to be cancelled?',
        'want_cancel' => 'Are you sure you want to cancel this reservation?',
        'check_in' => 'Do you want to mark as Checked in?',
        'check_out' => 'Do you want to mark as Checked out?'
    ],
    'observation' => 'Reason',
    'created' => 'Reservation created',
    'cancelled' => 'Reservation cancelled',
    'have_been_refund' => '$:amount have been refund',
    'booking' => [
        'have_question' => 'Have a question about your reservation? The best way to get information is to ask your host directly.',
        'no_longer_want' => 'If you no longer want this reservation',
        'more_info' => 'For more information contact the host',
        'host' => 'Your host, :user_name'
    ],
    'no_refund' => 'Refunds do not apply due to cancellation policy',
    'your_confirmation_number' => 'The confirmation number is :confirmation_number',
    'confirmation_number' => 'Confirmation Number',
    'amenities' => 'Amenities',
    'add_amenities' => 'Insert search criteria'
];