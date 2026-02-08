<?php

use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Enums\RsPaymentVia;

return [

    'show_title' => 'Manage users',
    'add_user_title' => 'Add new user',
    'edit_user_title' => 'Edit user',
    'delete_user_title' => 'Delete user',
    'password_reset' => 'Password reset',
    'enter_first_name' => 'Enter First Name',
    'enter_last_name' => 'Enter Last Name',
    'enter_phone_number' => 'Enter Phone Number',
    'enter_broker_cut' => 'Enter Broker Cut',
    'enter_pin' => 'Enter PIN',
    'enter_pin_edit' => 'Enter PIN to edit or leave it blank for no change',
    'enter_owner_commission' => 'Enter commission for sales',
    'enter_password_edit' => 'Enter password to edit or leave it blank for no change',
    'added' => 'User successfully added',
    'edited' => 'User successfully edited',
    'deleted' => 'User successfully deleted',
    'error_saving' => 'An error occurred while saving the user',
    'confirm_delete' => 'Please confirm you want to delete this user.',
    'not_found' => 'User not found.',
    'can_not_delete_current_user' => 'You can not delete the current user!',
    'error_deleting' => 'An error occurred while deleting the user',
    'no_changed_required' => 'No changed required',
    'last_login' => 'Last login',
    'phone_number' => 'Phone Number',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'email' => 'Email',
    'role_id' => 'Role',
    'broker_cut' => 'Broker Cut',
    'pin' => 'PIN',
    'password' => 'Password',
    'commission' => 'Commission for sales',
    'payment_via' => 'Incomes Payment by',
    'password_confirmation' => 'Confirm password',
    'roles' => [
        RoleType::CUSTOMER => 'Customer',
        RoleType::OWNER => 'Owner',
        RoleType::BROKER => 'Broker'
    ],
    'payment_vias' => [
        RsPaymentVia::CHECK => 'Check',
        RsPaymentVia::TRANSFER => 'Bank Transference',
        RsPaymentVia::CASH => 'Cash'
    ],
    'credentials' => 'Credentials'
];
