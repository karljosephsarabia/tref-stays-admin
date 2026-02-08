<?php

return [
    'error' => [
        'group_saving' => 'An error occurred while saving the Group',
        'criterion_saving' => 'An error occurred while saving the Criterion',
        'number' => 'A valid number required',
        'looking_up_address' => 'An error occurred while looking up address information',
        'invalid_address_lookup_data' => 'The received data from the API is invalid',
    ],
    'title' => [
        'show_table' => 'Manage Search Criteria',
        'edit_criterion' => 'Edit Criterion',
        'delete_criterion' => 'Delete Criterion',
        'add_criterion_type' => 'Add new criterion type',
        'add_criterion' => 'Add new criterion',
        'modal_add_criterion_type' => 'Create a New Criteria Group',
        'modal_edit_criterion_type' => 'Edit Criteria Group',
        'modal_add_criterion' => 'Create a New Criteria',
        'modal_edit_criterion' => 'Edit Criteria',
    ],
    'columns' =>[
        'type' => [
            'name' => 'Name',
            'menu_order' => 'Menu Order'
        ],
        'criteria' => [
           'name' => 'Name',
            'menu_order' => 'Menu Order',
           'type' => 'Type',
           'has_quantity' => 'Quantity',
           'has_distance' => 'Distance',
        ]
    ],

    'search_criteria' => 'Criteria',
    'name' => 'Name',
    'menu_order' => 'Menu Order',
    'has_quantity' => 'Use Quantity',
    'has_distance' => 'Use Distance',
    'loading' => 'Loading records',

    'type_added' => 'Criteria Group successfully added',
    'type_edited' => 'Criteria Group successfully edited',
    'type_deleted' => 'Criteria Group successfully deleted',
    'type_confirm_delete' => 'Please confirm you want to delete this Group.',

    'added' => 'Criteria successfully added',
    'edited' => 'Criteria successfully edited',
    'deleted' => 'Criteria successfully deleted',
    'confirm_delete' => 'Please confirm you want to delete this Criteria.',

    'updated' => 'Phone Location successfully updated',
    'number_has_change_first_save' => 'The number has changed, first save changes and then you may try to lookup for the address',
    'lookup_result_changed_data' => 'Address lookup has changed the data on this destination, please verify before saving',
    'is_commercial' => 'Commercial',
    'full_address' => 'Full Address',
    'postal_code' => 'Postal Code',
    'zip4' => 'Zip4',
    'house_number' => 'House Number',
    'enter' => [
        'name' => 'Enter Name',
        'menu_order' => 'Enter Menu Order',

        'phone_number' => 'Enter Phone Number',
        'postal_code' => 'Enter Postal Code',
        'zip4' => 'Enter Zip4',
        'city' => 'Enter City',
        'street_name' => 'Enter Street Name',
        'state' => 'Enter State',
        'country' => 'Enter Country',
        'house_number' => 'House Number'
    ]
];