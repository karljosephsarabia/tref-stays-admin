<?php

use SMD\Common\ReservationSystem\Enums\RsPaymentVia;

return [
    'ending_balance' => 'Ending balance',
    'starting_balance' => 'Starting balance',
    'total_reservations' => 'Total reservations',
    'total_payments' => 'Total payments',
    'total_refunds' => 'Total refunds',
    'total_broker_fees' => 'Total broker fees',
    'total_commissions' => 'Total system commissions',
    'incomes' => 'Incoming Reports',
    'date' => 'Date',
    'amount' => 'Amount (USD)',
    'description' => 'Description',
    'summary_created' => 'Summary Created',
    'client_name' => 'Client Name',
    'download_pdf' => 'Download',
    'pay_invoice' => 'Pay',
    'pay_invoice_modal' => 'Pay Invoice',
    'paid_at' => 'Paid at',
    'paid_via' => 'Paid using',
    'pay_via' => 'Pay using',
    'comment' => 'Comment',
    'payment_done' =>  'Payment Done',
    'payment_via' => [
        RsPaymentVia::CASH => 'Cash',
        RsPaymentVia::TRANSFER => 'Transfer',
        RsPaymentVia::CHECK => 'Check',
    ]
];