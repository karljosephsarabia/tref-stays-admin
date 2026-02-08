<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsPropertyImage;
use App\RsUser;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:rs_users',
            'phone_number' => 'required|numeric|digits_between:10,15|unique:rs_users',
            'pin' => 'required|numeric|digits_between:4,6',
            'password' => 'required|string|min:6|confirmed'
        ];

        // Add role_id validation only if it's provided (for renter)
        if (isset($data['role_id'])) {
            $rules['role_id'] = 'required|in:' . join(',', array_keys(RoleType::REGISTER));
        }

        // Add property validation rules for owner registration
        if (isset($data['role']) && $data['role'] === 'owner') {
            $rules['property_title'] = 'required|string|max:255';
            $rules['property_type'] = 'required|string';
            $rules['bedrooms'] = 'required|integer|min:1';
            $rules['bathrooms'] = 'required|integer|min:1';
            $rules['max_guests'] = 'required|integer|min:1';
            $rules['price_per_night'] = 'required|numeric|min:0';
            $rules['address'] = 'required|string';
            $rules['city'] = 'required|string';
            $rules['state'] = 'required|string';
            $rules['country'] = 'required|string';
            $rules['zipcode'] = 'required|string';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register')->with('roles', RoleType::REGISTER);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return RsUser
     */
    protected function create(array $data)
    {
        // Determine role_id: use role field for owners, role_id for renters
        $roleId = isset($data['role']) && $data['role'] === 'owner' 
            ? RoleType::OWNER 
            : $data['role_id'];

        return RsUser::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'role_id' => $roleId,
            'phone_number' => $data['phone_number'],
            'pin' => $data['pin'],
            'password' => bcrypt($data['password']),
            'activated' => true,  // Auto-activate new users
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Log registration attempt for debugging
        \Log::info('Registration attempt', [
            'role' => $request->role,
            'role_id' => $request->role_id,
            'email' => $request->email,
            'all_fields' => array_keys($request->all())
        ]);

        try {
            $this->validator($request->all())->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Registration validation failed', [
                'errors' => $e->errors()
            ]);
            throw $e;
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        // If this is an owner registration, create the property and redirect to owner dashboard
        if ($request->role === 'owner') {
            $this->createProperty($request, $user);
            return redirect()->route('owner.dashboard')->with('success', 'Welcome! Your property has been listed successfully.');
        }

        // Renter registration - redirect to renter dashboard
        return redirect()->route('renter.dashboard')->with('success', 'Welcome to Tref Stays! Start exploring properties.');
    }

    /**
     * Create property for owner after registration
     */
    protected function createProperty(Request $request, RsUser $user)
    {
        // Create kosher info array
        $kosherInfo = [
            'kosher_kitchen' => $request->kosher_kitchen ? true : false,
            'shabbos_friendly' => $request->shabbos_friendly ? true : false,
            'nearby_shul' => [
                'name' => $request->nearby_shul ?? '',
                'distance' => $request->nearby_shul_distance ?? ''
            ],
            'nearby_kosher_shops' => [
                'name' => $request->nearby_kosher_shops ?? '',
                'distance' => $request->nearby_kosher_shops_distance ?? ''
            ],
            'nearby_mikva' => [
                'name' => $request->nearby_mikva ?? '',
                'distance' => $request->nearby_mikva_distance ?? ''
            ]
        ];

        // Create the property
        $property = RsProperty::create([
            'owner_id' => $user->id,
            'title' => $request->property_title,
            'property_type' => $request->property_type,
            'bedroom_count' => $request->bedrooms,
            'bathroom_count' => $request->bathrooms,
            'guest_count' => $request->max_guests,
            'price' => $request->price_per_night,
            'street_name' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zipcode_id' => $request->zipcode,
            'additional_information' => $request->description ?? '',
            'amenities' => json_encode($request->amenities ?? []),
            'kosher_info' => json_encode($kosherInfo),
            'currency' => $request->currency ?? 'USD',
            'active' => true,
            'is_paused' => false,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $mainImageIndex = $request->input('main_image', 0);

            foreach ($images as $index => $image) {
                $filename = time() . '_' . $index . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('property_images/' . $property->id, $filename, 'public');
                
                RsPropertyImage::create([
                    'property_id' => $property->id,
                    'image_url' => '/storage/' . $path,
                    'is_primary' => $index == $mainImageIndex,
                    'sort_order' => $index,
                    'active' => true,
                ]);
            }
        }

        return $property;
    }
}
