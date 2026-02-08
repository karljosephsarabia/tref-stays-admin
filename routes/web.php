<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use SMD\Common\ReservationSystem\Enums\RoleType;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Tref Stays Welcome Page (public homepage)
Route::get('/', function () {
    return view('tref.welcome');
});

// Tref Stays Auth Pages (custom design - before Auth::routes())
Route::get('/login', function () {
    return view('tref.login');
})->name('login')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

Route::get('/register', function () {
    return view('tref.register');
})->name('register')->middleware('guest');

Route::post('/register', [RegisterController::class, 'register'])->middleware('guest');

// Auth routes (POST handlers for logout, password reset, etc)
Auth::routes(['login' => false, 'register' => false]);

// Tref Stays Search & Property Pages
Route::get('/search', function () {
    return view('tref.search');
})->name('tref.search');

Route::get('/property/{id}', function ($id) {
    // Fetch real property from database
    $dbProperty = \SMD\Common\ReservationSystem\Models\RsProperty::with(['owner', 'images', 'reservations'])->find($id);
    
    if ($dbProperty) {
        // Transform database property to the format expected by the view
        $location = implode(', ', array_filter([$dbProperty->city, $dbProperty->state])) ?: 'Location not set';
        $property = (object)[
            'id' => $dbProperty->id,
            'title' => $dbProperty->title,
            'type' => $dbProperty->property_type ?? 'Short Term Rent',
            'location' => $location,
            'price' => $dbProperty->price ?? 0,
            'guests' => $dbProperty->guest_count ?? 1,
            'beds' => $dbProperty->bed_count ?? 1,
            'baths' => $dbProperty->bathroom_count ?? 1,
            'rating' => 4.8,  // You could add a rating field to the database
            'reviews' => $dbProperty->reservations->count(),
            'description' => $dbProperty->additional_information,
            'images' => $dbProperty->images->pluck('image_url')->toArray(),
            'amenities' => json_decode($dbProperty->amenities ?? '[]', true),
            'kosher_info' => json_decode($dbProperty->kosher_info ?? '{}', true),
            'owner' => $dbProperty->owner,
            'cleaning_fee' => $dbProperty->cleaning_fee ?? 0,
            'checkin_time' => $dbProperty->checkin_time ?? '3:00 PM',
            'checkout_time' => $dbProperty->checkout_time ?? '11:00 AM',
            'house_rules' => $dbProperty->house_rules,
            'address' => $dbProperty->map_address,
        ];
    } else {
        // Fallback sample property if not found
        $property = (object)['id' => $id, 'title' => 'Property Not Found', 'type' => 'Short Term Rent', 'location' => 'Unknown', 'price' => 0, 'guests' => 0, 'beds' => 0, 'baths' => 0, 'rating' => 0, 'reviews' => 0];
    }
    return view('tref.property-detail', ['property' => $property]);
})->name('tref.property');

// Dashboard for authenticated users
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/history', 'SearchController@history')->name('history');

// Owner Dashboard Routes
Route::group(['prefix' => 'owner', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', 'OwnerDashboardController@index')->name('owner.dashboard');
    Route::get('/property/{id}/edit', 'OwnerDashboardController@editProperty')->name('owner.property.edit');
    Route::put('/property/{id}', 'OwnerDashboardController@updateProperty')->name('owner.property.update');
    Route::delete('/property/image/{id}', 'OwnerDashboardController@deleteImage')->name('owner.property.image.delete');
    Route::get('/inquiries', 'OwnerDashboardController@inquiries')->name('owner.inquiries');
    Route::get('/inquiry/{id}', 'OwnerDashboardController@showInquiry')->name('owner.inquiry.show');
    Route::put('/inquiry/{id}', 'OwnerDashboardController@updateInquiry')->name('owner.inquiry.update');
    Route::get('/analytics', 'OwnerDashboardController@analytics')->name('owner.analytics');
});

// Renter Dashboard Routes
Route::group(['prefix' => 'renter', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', 'RenterDashboardController@index')->name('renter.dashboard');
    Route::get('/saved', 'RenterDashboardController@savedProperties')->name('renter.saved');
    Route::post('/save/{propertyId}', 'RenterDashboardController@toggleSave')->name('renter.toggle-save');
    Route::get('/messages', 'RenterDashboardController@messages')->name('renter.messages');
    Route::get('/conversation/{id}', 'RenterDashboardController@showConversation')->name('renter.conversation');
    Route::post('/message', 'RenterDashboardController@sendMessage')->name('renter.send-message');
    Route::get('/start-conversation/{propertyId}', 'RenterDashboardController@startConversation')->name('renter.start-conversation');
    Route::get('/reviews', 'RenterDashboardController@reviews')->name('renter.reviews');
    Route::get('/review/{propertyId}', 'RenterDashboardController@showReviewForm')->name('renter.review-form');
    Route::post('/review/{propertyId}', 'RenterDashboardController@submitReview')->name('renter.submit-review');
});

Route::group(['prefix' => 'search'], function () {
    Route::get('/', 'SearchController@search')->name('search');
    Route::get('/{id}', 'SearchController@room')->name('room')->middleware(['auth']);
    Route::get('/{id}/view', 'SearchController@room')->name('room_no_auth');
    Route::post('/rooms', 'SearchController@rooms')->name('rooms');
    Route::post('/map-location/{id?}', 'PropertyController@mapLocation')->name('set_map_location');

    Route::get('/reserve/{id}', 'SearchController@embeddedLink')->name('search_embedded_link');
});

Route::group(['prefix' => 'lookup'], function () {
    Route::get('/users/{role}', 'LookupController@users')->name('users_lookup')->middleware(['auth']);
    Route::get('/reserved-dates/{id}', 'LookupController@reservedDates')->name('reserved_dates');
    Route::get('/phone-location/{number?}', 'LookupController@addressLookup')->name('phone_location_lookup')->middleware(['auth']);
    Route::get('/zipcode-lookup', 'LookupController@zipcodeLookup')->name('zipcode_lookup');
    Route::get('/reverse-zipcode-lookup/{zipcode}', 'LookupController@reverseZipcodeLookup')->name('reverse_zipcode_lookup');
    Route::get('/amenity-lookup', 'LookupController@amenityLookup')->name('amenity_lookup');
    Route::get('/reverse-amenity-lookup', 'LookupController@reverseAmenityLookup')->name('reverse_amenity_lookup');
});

Route::group(['prefix' => 'report', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'income', 'middleware' => [middleware_role([RoleType::OWNER, RoleType::BROKER])]], function () {
        Route::get('/{user?}', 'ReportController@income')->name('income_report');
        Route::get('/{user}/pdf/{id?}', 'ReportController@incomePdf')->name('income_pdf');
        Route::post('/pay', 'ReportController@incomePay')->name('income_pay');
    });
});

Route::group(['prefix' => 'address-location', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
    Route::get('/', 'PhoneLocationController@show')->name('addresses');
    Route::get('/datatable', 'PhoneLocationController@datatable')->name('addresses_datatable');
    Route::get('/edit/{id?}', 'PhoneLocationController@show')->name('address_get_edit');
    Route::post('/add-edit', 'PhoneLocationController@update')->name('address_add_edit');
    Route::post('/delete/{id?}', 'PhoneLocationController@delete')->name('address_delete');
});

Route::group(['prefix' => 'reservations', 'middleware' => ['auth']], function () {
    Route::get('/', 'ReservationController@index')->name('reservations');
    Route::get('/{id}/details', 'ReservationController@details')->name('reservation_details');
    Route::post('/{id}/reserve', 'ReservationController@reserve')->name('reserve_room');
    Route::post('/{id}/cancel', 'ReservationController@cancel')->name('cancel_reservation');
    Route::post('/{id}/status', 'ReservationController@status')->name('reservation_status');
    Route::get('/datatable', 'ReservationController@datatable')->name('reservations_datatable');
});

Route::group(['prefix' => 'account', 'middleware' => ['auth']], function () {

    Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {
        Route::get('/', 'AccountController@index')->name('profile');
        Route::post('/edit', 'AccountController@profile')->name('profile_edit');
        Route::post('/image', 'AccountController@image')->name('profile_image');
        Route::get('/stripe_reload', 'AccountController@stripeReload')->name('stripe_reload');
        Route::post('/fee_config/{id?}', 'AccountController@getUserFeeConfig')->name('user_fee_config');
    });

    Route::group(['prefix' => 'sources', 'middleware' => ['auth']], function () {
        Route::get('/{source?}/{user?}', 'AccountController@sources')->name('sources');
        Route::post('/edit', 'AccountController@editSource')->name('edit_source');
        Route::post('/delete', 'AccountController@deleteSource')->name('delete_source');
        Route::post('/add', 'AccountController@addSource')->name('add_source');
        Route::post('/verify', 'AccountController@verifySource')->name('verify_source');
        Route::post('/set-default', 'AccountController@setDefaultSource')->name('default_source');

        Route::post('/payment', 'AccountController@creditCardPayment')->name('cc_payment');
    });
});

Route::group(['prefix' => 'manager'], function () {
    Route::group(['prefix' => 'users', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
        Route::get('/settings', 'HomeController@setting')->name('setting');
    });

    Route::group(['prefix' => 'properties', 'middleware' => ['auth', middleware_role([RoleType::OWNER, RoleType::BROKER])]], function () {
        Route::get('/', 'PropertyController@show')->name('properties');
        Route::get('/datatable', 'PropertyController@datatable')->name('properties_datatable');
        Route::get('/edit/{id?}', 'PropertyController@show')->name('property_get_edit');
        Route::post('/add', 'PropertyController@add')->name('property_add');
        Route::post('/check_add', 'PropertyController@check_add')->name('property_check_add');
        Route::post('/edit/{id?}', 'PropertyController@edit')->name('property_edit');
        Route::post('/delete/{id?}', 'PropertyController@delete')->name('property_delete');
        Route::post('/images/{id?}/{action?}', 'PropertyController@images')->name('property_images');
        Route::post('/availabilities/{id?}/{action?}', 'PropertyController@availabilities')->name('property_availabilities');

        Route::post('/criteria/{id?}', 'PropertyController@criteriaDatatable')->name('properties_criteria');
        Route::post('/criteria/save/{id?}', 'PropertyController@saveCriteria')->name('save_property_criteria');
        Route::post('/criteria/delete/{pid?}/{cid?}', 'PropertyController@deleteCriteria')->name('delete_property_criteria');

        Route::post('/streetnames/{id?}', 'PropertyController@fetchStreetNames')->name('street_names');
        Route::post('/areaname/{id?}', 'PropertyController@fetchAreaName')->name('area_name');

        Route::post('/pause/{id?}', 'PropertyController@pause')->name('property_pause');
        Route::post('/restrictions/{id?}/{action?}', 'PropertyController@restrictions')->name('property_restrictions');

        Route::post('/packages/{id?}/{action?}', 'PropertyController@packages')->name('property_packages');

    });

    Route::group(['prefix' => 'users', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
        Route::get('/', 'UserController@show')->name('users');
        Route::get('/datatable', 'UserController@datatable')->name('users_datatable');
        Route::get('/edit/{id?}', 'UserController@show')->name('user_get_edit');
        Route::post('/add', 'UserController@add')->name('user_add');
        Route::post('/edit/{id?}', 'UserController@edit')->name('user_edit');
        Route::post('/delete/{id?}', 'UserController@delete')->name('user_delete');
    });

    Route::group(['prefix' => 'areas', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
        Route::get('/', 'AreaController@index')->name('areas');
        Route::get('/show', 'AreaController@show')->name('areas_show');
        Route::post('/add-edit', 'AreaController@addEdit')->name('area_add_edit');
        Route::post('/delete/{id?}', 'AreaController@delete')->name('area_delete');
        Route::group(['prefix' => 'zipcode'], function () {
            Route::post('/add-edit', 'AreaController@addEditZipcode')->name('area_zipcode_add_edit');
            Route::post('/delete/{id?}', 'AreaController@deleteZipcode')->name('area_zipcode_delete');
        });
    });


    Route::group(['prefix' => 'menu', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
        Route::get('/', 'CustomMenuController@show')->name('custom-menu');
        Route::get('/play/{id?}', 'CustomMenuController@play')->name('recording_play');
        Route::post('/add', 'CustomMenuController@menuAdd')->name('menu_add');
        Route::post('/edit/{id?}', 'CustomMenuController@menuEdit')->name('menu_edit');
        Route::post('/delete/{id?}', 'CustomMenuController@menuDelete')->name('menu_delete');

        Route::post('/option/add', 'CustomMenuController@optionAdd')->name('option_add');
        Route::post('/option/edit/{id?}', 'CustomMenuController@optionEdit')->name('option_edit');
        Route::post('/option/delete/{id?}', 'CustomMenuController@optionDelete')->name('option_delete');
    });

    Route::group(['prefix' => 'point-interests', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
        Route::get('/', 'PointInterestController@index')->name('point_interests');
        Route::get('/show', 'PointInterestController@show')->name('point_interests_show');
        Route::post('/add-edit', 'PointInterestController@addEdit')->name('point_interest_add_edit');
        Route::post('/delete/{id}', 'PointInterestController@delete')->name('point_interest_delete');
    });

    Route::group(['prefix' => 'criteria', 'middleware' => [middleware_role([RoleType::BROKER])]], function () {
        Route::get('/', 'CriterionController@show')->name('criteria');
        Route::get('/datatable', 'CriterionController@datatable')->name('criteria_datatable');
        Route::post('/group/add', 'CriterionController@addType')->name('criterion_type_add');
        Route::post('/group/edit/{id?}', 'CriterionController@editType')->name('criterion_type_edit');
        Route::post('/group/delete/{id?}', 'CriterionController@deleteType')->name('criterion_type_delete');

        Route::post('/add', 'CriterionController@add')->name('criterion_add');
        Route::post('/edit/{id?}', 'CriterionController@edit')->name('criterion_edit');
        Route::post('/delete/{id?}', 'CriterionController@delete')->name('criterion_delete');

        Route::post('/list/{id?}', 'CriterionController@getCriteriaByType')->name('criteria_by_type');
    });

});

Route::group(['prefix' => 'userlogs', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
    Route::get('/', 'UserLogController@index')->name('user_logs');
    Route::get('/datatable', 'UserLogController@datatable')->name('user_logs_datatable');
});

Route::group(['prefix' => 'hebrew_audios', 'middleware' => ['auth', middleware_role([RoleType::BROKER])]], function () {
    Route::get('/', 'HebrewAudioController@show')->name('hebrew_audio');
    Route::get('/datatable', 'HebrewAudioController@datatable')->name('hebrew_audio_datatable');
    Route::post('/edit/{id?}', 'HebrewAudioController@edit')->name('hebrew_audio_edit');
    Route::get('/english_play/{id?}', 'HebrewAudioController@english_play')->name('english_recording_play');
    Route::get('/hebrew_play/{id?}', 'HebrewAudioController@hebrew_play')->name('hebrew_recording_play');
});

// Admin Routes
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // Admin authentication routes (accessible without login)
    Route::get('/login', 'AdminAuthController@showLoginForm')->name('admin.login');
    Route::post('/login', 'AdminAuthController@login');
    Route::get('/logout', 'AdminAuthController@logout')->name('admin.logout');
    
    // Protected admin routes (require admin login)
    Route::group(['middleware' => ['auth']], function () {
        // Dashboard
        Route::get('/', 'AdminController@dashboard')->name('admin.dashboard');
        Route::get('/dashboard', 'AdminController@dashboard');
        Route::get('/stats', 'AdminController@getStats')->name('admin.stats');
        
        // User management
        Route::get('/users', 'AdminController@users')->name('admin.users');
        Route::get('/users/{id}', 'AdminController@userDetails')->name('admin.users.details');
        Route::post('/users/create', 'AdminController@createUser')->name('admin.users.create');
        Route::put('/users/{id}', 'AdminController@updateUser')->name('admin.users.update');
        Route::post('/users/{id}/status', 'AdminController@updateUserStatus')->name('admin.users.status');
        Route::delete('/users/{id}', 'AdminController@deleteUser')->name('admin.users.delete');
        
        // Property management
        Route::get('/properties', 'AdminController@properties')->name('admin.properties');
        Route::get('/properties/new', 'AdminController@propertyCreateForm')->name('admin.properties.new');
        Route::post('/properties/create', 'AdminController@createProperty')->name('admin.properties.create');
        Route::get('/properties/{id}', 'AdminController@propertyDetails')->name('admin.properties.details');
        Route::put('/properties/{id}', 'AdminController@updateProperty')->name('admin.properties.update');
        Route::post('/properties/{id}/toggle', 'AdminController@togglePropertyStatus')->name('admin.properties.toggle');
        Route::delete('/properties/{id}', 'AdminController@deleteProperty')->name('admin.properties.delete');
        
        // Custom Amenities management
        Route::post('/amenities/custom', 'AdminController@storeCustomAmenity')->name('admin.amenities.store');
        Route::delete('/amenities/custom/{id}', 'AdminController@deleteCustomAmenity')->name('admin.amenities.delete');
        
        // Reservation management
        Route::get('/reservations', 'AdminController@reservations')->name('admin.reservations');
        Route::get('/reservations/{id}', 'AdminController@reservationDetails')->name('admin.reservations.details');
        Route::post('/reservations/{id}/status', 'AdminController@updateReservationStatus')->name('admin.reservations.status');
        Route::post('/reservations/{id}/cancel', 'AdminController@cancelReservation')->name('admin.reservations.cancel');
        
        // Financial reports
        Route::get('/finances', 'AdminController@finances')->name('admin.finances');
        
        // Analytics
        Route::get('/analytics', 'AdminController@analytics')->name('admin.analytics');
        
        // Settings
        Route::get('/settings', 'AdminController@settings')->name('admin.settings');
        Route::post('/settings', 'AdminController@updateSettings')->name('admin.settings.update');
        
        // Bulk actions & Export
        Route::post('/bulk-action', 'AdminController@bulkAction')->name('admin.bulk-action');
        Route::get('/export', 'AdminController@exportData')->name('admin.export');
    });
});