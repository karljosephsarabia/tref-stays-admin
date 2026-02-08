<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SMD\Common\ReservationSystem\Enums\RoleType;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Models\RsReservation;
use SMD\Common\ReservationSystem\Models\RsTransaction;
use SMD\Common\ReservationSystem\Models\RsPropertyImage;
use SMD\Common\ReservationSystem\Models\RsUserFeeConfiguration;
use App\RsUser;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Check if user is admin (role_id 1) or broker (role_id 2)
            if (!Auth::user() || (Auth::user()->role_id != 1 && Auth::user()->role_id != 2)) {
                return redirect()->route('login')->with('error', 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Show admin dashboard with comprehensive analytics
     */
    public function dashboard()
    {
        // User Statistics
        $totalUsers = RsUser::count();
        $totalAdmins = RsUser::where('role_id', 1)->count(); // Admin role
        $totalBrokers = RsUser::where('role_id', 2)->count(); // Broker role
        $totalOwners = RsUser::where('role_id', 3)->count(); // Owner role
        $totalCustomers = RsUser::whereIn('role_id', [4, 5])->count(); // Customer/Renter roles
        $activeUsers = RsUser::where('active', true)->where('activated', true)->count();
        
        // Property Statistics
        $totalProperties = RsProperty::count();
        $activeProperties = RsProperty::where('active', true)->count();
        
        // Reservation Statistics
        $totalReservations = RsReservation::count();
        $pendingReservations = RsReservation::where('status', 1)->count();
        $confirmedReservations = RsReservation::where('status', 2)->count();
        $completedReservations = RsReservation::where('status', 4)->count();
        $cancelledReservations = RsReservation::where('status', 3)->count();
        
        // Financial Statistics
        $totalRevenue = RsReservation::where('active', true)->sum('total_price');
        $thisMonthRevenue = RsReservation::where('active', true)
            ->whereRaw("EXTRACT(MONTH FROM created_at) = ?", [date('m')])
            ->whereRaw("EXTRACT(YEAR FROM created_at) = ?", [date('Y')])
            ->sum('total_price');
        $lastMonthRevenue = RsReservation::where('active', true)
            ->whereRaw("EXTRACT(MONTH FROM created_at) = ?", [date('m', strtotime('-1 month'))])
            ->whereRaw("EXTRACT(YEAR FROM created_at) = ?", [date('Y', strtotime('-1 month'))])
            ->sum('total_price');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 0;
        
        // Recent Activity
        $recentUsers = RsUser::orderBy('created_at', 'desc')->limit(5)->get();
        $recentReservations = RsReservation::with(['property', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $recentProperties = RsProperty::with('owner')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Monthly Revenue Data
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months"));
            $year = date('Y', strtotime("-$i months"));
            $revenue = RsReservation::where('active', true)
                ->whereRaw("EXTRACT(MONTH FROM created_at) = ?", [$month])
                ->whereRaw("EXTRACT(YEAR FROM created_at) = ?", [$year])
                ->sum('total_price');
            $monthlyRevenue[] = [
                'month' => date('M Y', strtotime("-$i months")),
                'revenue' => $revenue
            ];
        }
        
        $reservationsByStatus = [
            ['status' => 'Pending', 'count' => $pendingReservations, 'color' => '#f59e0b'],
            ['status' => 'Confirmed', 'count' => $confirmedReservations, 'color' => '#10b981'],
            ['status' => 'Completed', 'count' => $completedReservations, 'color' => '#3b82f6'],
            ['status' => 'Cancelled', 'count' => $cancelledReservations, 'color' => '#ef4444'],
        ];
        
        $usersByRole = [
            ['role' => 'Admins', 'count' => $totalAdmins, 'color' => '#8b5cf6'],
            ['role' => 'Brokers', 'count' => $totalBrokers, 'color' => '#06b6d4'],
            ['role' => 'Owners', 'count' => $totalOwners, 'color' => '#10b981'],
            ['role' => 'Customers', 'count' => $totalCustomers, 'color' => '#f59e0b'],
        ];

        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalAdmins', 
            'totalBrokers', 
            'totalOwners', 
            'totalCustomers',
            'activeUsers',
            'totalProperties',
            'activeProperties',
            'totalReservations',
            'pendingReservations',
            'confirmedReservations',
            'completedReservations',
            'cancelledReservations',
            'totalRevenue',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'revenueGrowth',
            'recentUsers',
            'recentReservations',
            'recentProperties',
            'monthlyRevenue',
            'reservationsByStatus',
            'usersByRole'
        ));
    }

    /**
     * Show all users with filtering
     */
    public function users(Request $request)
    {
        $query = RsUser::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active', true)->where('activated', true);
            } else {
                $query->where(function($q) {
                    $q->where('active', false)->orWhere('activated', false);
                });
            }
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = [
            RoleType::ADMIN => 'Admin',
            RoleType::BROKER => 'Broker',
            RoleType::OWNER => 'Owner',
            RoleType::CUSTOMER => 'Customer',
            RoleType::RENTER => 'Renter',
            RoleType::GUEST => 'Guest',
        ];
        
        return view('admin.users', compact('users', 'roles'));
    }

    /**
     * Show single user details with all related data
     */
    public function userDetails($id)
    {
        $user = RsUser::findOrFail($id);
        
        // Get properties owned by this user
        $properties = RsProperty::where('owner_id', $id)->with(['images'])->get();
        
        // Get reservations made by this user (as customer)
        $reservationsAsCustomer = RsReservation::where('customer_id', $id)
            ->with(['property'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get reservations for properties owned by this user (as host)
        $propertyIds = $properties->pluck('id')->toArray();
        $reservationsAsHost = RsReservation::whereIn('property_id', $propertyIds)
            ->with(['property', 'customer'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get transactions for this user
        $transactions = RsTransaction::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Notifications - empty collection (model doesn't exist yet)
        $notifications = new Collection();
        
        // Credit cards - empty collection (model doesn't exist yet)
        $creditCards = new Collection();
        
        // Activities - empty collection (model doesn't exist yet)
        $activities = new Collection();
        
        // Get fee configurations
        $feeConfig = null;
        try {
            $feeConfig = RsUserFeeConfiguration::where('rs_user_id', $id)->first();
        } catch (\Exception $e) {
            // Model may not exist
        }
        
        // Incoming reports - empty collection (model doesn't exist yet)
        $incomingReports = new Collection();
        
        // Calculate statistics
        $stats = [
            'total_properties' => $properties->count(),
            'total_reservations_made' => $reservationsAsCustomer->count(),
            'total_reservations_received' => $reservationsAsHost->count(),
            'total_spent' => $reservationsAsCustomer->sum('total_price'),
            'total_earned' => $reservationsAsHost->sum('total_price'),
            'active_reservations' => $reservationsAsCustomer->where('status', 1)->count() + $reservationsAsHost->where('status', 1)->count(),
        ];
        
        return view('admin.user-details', compact(
            'user', 
            'properties', 
            'reservationsAsCustomer', 
            'reservationsAsHost', 
            'transactions', 
            'notifications', 
            'creditCards', 
            'activities',
            'feeConfig',
            'incomingReports',
            'stats'
        ));
    }

    /**
     * Create a new user
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:rs_users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|in:admin,broker,owner,customer,renter,guest',
        ]);

        $user = new RsUser();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $request->role_id;
        $user->phone_number = $request->phone_number;
        $user->pin = $request->pin;
        $user->active = true;
        $user->activated = true;
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User created successfully!', 'user' => $user]);
        }
        
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = RsUser::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:rs_users,email,' . $id,
            'role_id' => 'required|in:admin,broker,owner,customer,renter,guest',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->phone_number = $request->phone_number;
        $user->address_1 = $request->address_1;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zipcode = $request->zipcode;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User updated successfully!']);
        }
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    /**
     * Update user status
     */
    public function updateUserStatus(Request $request, $id)
    {
        $user = RsUser::findOrFail($id);
        $user->active = $request->active ? 1 : 0;
        $user->activated = $request->activated ? 1 : 0;
        $user->save();

        return response()->json(['success' => true, 'message' => 'User status updated successfully']);
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = RsUser::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'You cannot delete your own account']);
        }
        
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /**
     * Show all properties
     */
    public function properties(Request $request)
    {
        $query = RsProperty::with(['owner', 'images']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('map_address', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }
        
        $properties = $query->orderBy('created_at', 'desc')->paginate(20);
        $owners = RsUser::whereIn('role_id', [3, 2])->get(); // Owner and Broker roles
        
        return view('admin.properties', compact('properties', 'owners'));
    }

    /**
     * Show property details
     */
    public function propertyDetails($id)
    {
        $property = RsProperty::with(['owner', 'images', 'reservations.customer'])->findOrFail($id);
        return view('admin.property-details', compact('property'));
    }

    /**
     * Show property creation wizard
     */
    public function propertyCreateForm()
    {
        $owners = RsUser::whereIn('role_id', [3, 2, 1])->get(); // Owner, Broker, and Admin roles
        $customAmenities = \App\SMD\RsCustomAmenity::active()->general()->orderBy('sort_order')->get();
        $customKosherAmenities = \App\SMD\RsCustomAmenity::active()->kosher()->orderBy('sort_order')->get();
        return view('admin.property-create', compact('owners', 'customAmenities', 'customKosherAmenities'));
    }

    /**
     * Store a new custom amenity with optional SVG icon
     */
    public function storeCustomAmenity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:general,kosher',
            'icon' => 'nullable|file|mimes:svg|max:512',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->name);
        
        // Check if already exists
        $existing = \App\SMD\RsCustomAmenity::where('slug', $slug)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This amenity already exists',
                'amenity' => $existing
            ], 422);
        }

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = $slug . '_' . time() . '.svg';
            $iconPath = $file->storeAs('amenity-icons', $filename, 'public');
        }

        $amenity = \App\SMD\RsCustomAmenity::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon_path' => $iconPath,
            'type' => $request->type,
            'is_active' => true,
            'sort_order' => \App\SMD\RsCustomAmenity::where('type', $request->type)->max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Custom amenity created successfully',
            'amenity' => [
                'id' => $amenity->id,
                'name' => $amenity->name,
                'slug' => $amenity->slug,
                'icon_html' => $amenity->icon_html,
                'icon_path' => $amenity->icon_path ? asset('storage/' . $amenity->icon_path) : null,
                'type' => $amenity->type,
            ]
        ]);
    }

    /**
     * Delete a custom amenity
     */
    public function deleteCustomAmenity($id)
    {
        $amenity = \App\SMD\RsCustomAmenity::findOrFail($id);
        
        // Delete icon file if exists
        if ($amenity->icon_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($amenity->icon_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($amenity->icon_path);
        }
        
        $amenity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Custom amenity deleted successfully'
        ]);
    }

    /**
     * Create property
     */
    public function createProperty(Request $request)
    {
        \Log::info('createProperty START');
        
        try {
            \Log::info('Validating request...');
            $request->validate([
                'title' => 'required|string|max:255',
                'owner_id' => 'required|exists:rs_users,id',
                'price' => 'required|numeric|min:0',
            ]);
            \Log::info('Validation passed');

            // Build amenities JSON from checkboxes and custom amenities
            $amenities = $request->amenities ?? [];
            $customAmenities = $request->custom_amenities ?? [];
            $allAmenities = array_merge($amenities, $customAmenities);

            // Build kosher info JSON
        $kosherInfo = [
            'kosher_kitchen' => $request->boolean('kosher_kitchen'),
            'shabbos_friendly' => $request->boolean('shabbos_friendly'),
            'nearby_shul' => [
                'name' => $request->nearby_shul_name,
                'distance' => $request->nearby_shul_distance,
            ],
            'nearby_shop' => [
                'name' => $request->nearby_shop_name,
                'distance' => $request->nearby_shop_distance,
            ],
            'nearby_mikva' => [
                'name' => $request->nearby_mikva_name,
                'distance' => $request->nearby_mikva_distance,
            ],
        ];

        $property = RsProperty::create([
            'title' => $request->title,
            'owner_id' => $request->owner_id,
            'price' => $request->price,
            'cleaning_fee' => $request->cleaning_fee ?? 0,
            'guest_count' => $request->guest_count ?? 1,
            'bed_count' => $request->bed_count ?? 1,
            'bedroom_count' => $request->bedroom_count ?? 1,
            'bathroom_count' => $request->bathroom_count ?? 1,
            'property_type' => $request->property_type ?? 1,
            'street_name' => $request->street_name,
            'house_number' => $request->house_number,
            'city' => $request->city,
            'state' => $request->state,
            'zipcode' => $request->zipcode,
            'country' => $request->country ?? 'US',
            'map_address' => $request->map_address,
            'additional_information' => $request->additional_information,
            'tagline' => $request->tagline,
            'neighborhood' => $request->neighborhood,
            'house_rules' => $request->house_rules,
            'checkin_time' => $request->checkin_time,
            'checkout_time' => $request->checkout_time,
            'amenities' => json_encode($allAmenities),
            'kosher_info' => json_encode($kosherInfo),
            'show_on_homepage' => $request->boolean('show_on_homepage'),
            'homepage_order' => $request->homepage_order,
            'featured_badge' => $request->featured_badge,
            'highlight_color' => $request->highlight_color ?: '#FF385C',
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'hero_cta_text' => $request->hero_cta_text,
            'hero_cta_url' => $request->hero_cta_url,
            'banner_image_url' => $request->banner_image_url,
            'display_layout' => $request->display_layout,
            'custom_css' => $request->custom_css,
            'custom_js' => $request->custom_js,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
            'spotlight_message' => $request->spotlight_message,
            'allow_instant_booking' => $request->boolean('allow_instant_booking'),
            'is_luxury_tier' => $request->boolean('is_luxury_tier'),
            'active' => true,
        ]);
        
        \Log::info('Property created', ['id' => $property->id]);

        // Handle media file uploads
        if ($request->hasFile('media_files') || $request->has('media_files')) {
            $mediaFiles = $request->file('media_files') ?? [];
            $mediaPrimary = $request->input('media_primary') ?? [];
            
            foreach ($mediaFiles as $index => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('property_images/' . $property->id, $filename, 'public');
                    
                    RsPropertyImage::create([
                        'property_id' => $property->id,
                        'image_url' => '/storage/' . $path,
                        'is_primary' => isset($mediaPrimary[$index]) && $mediaPrimary[$index] === '1',
                        'sort_order' => $index,
                        'active' => true,
                    ]);
                }
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Property created successfully!', 
                'property_id' => $property->id
            ]);
        }
        
        return redirect()->route('admin.properties')->with('success', 'Property created successfully!');
        
        } catch (\Exception $e) {
            \Log::error('Property creation error: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update property
     */
    public function updateProperty(Request $request, $id)
    {
        $property = RsProperty::findOrFail($id);
        
        $property->update([
            'title' => $request->title,
            'owner_id' => $request->owner_id,
            'price' => $request->price,
            'guest_count' => $request->guest_count,
            'bed_count' => $request->bed_count,
            'bedroom_count' => $request->bedroom_count,
            'bathroom_count' => $request->bathroom_count,
            'street_name' => $request->street_name,
            'house_number' => $request->house_number,
            'map_address' => $request->map_address,
            'additional_information' => $request->additional_information,
            'show_on_homepage' => $request->boolean('show_on_homepage'),
            'homepage_order' => $request->homepage_order,
            'featured_badge' => $request->featured_badge,
            'highlight_color' => $request->highlight_color ?: '#FF385C',
            'hero_title' => $request->hero_title,
            'hero_subtitle' => $request->hero_subtitle,
            'hero_cta_text' => $request->hero_cta_text,
            'hero_cta_url' => $request->hero_cta_url,
            'banner_image_url' => $request->banner_image_url,
            'display_layout' => $request->display_layout,
            'custom_css' => $request->custom_css,
            'custom_js' => $request->custom_js,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
            'spotlight_message' => $request->spotlight_message,
            'allow_instant_booking' => $request->boolean('allow_instant_booking'),
            'is_luxury_tier' => $request->boolean('is_luxury_tier'),
            'active' => $request->has('active') ? $request->boolean('active') : $property->active,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Property updated successfully!']);
        }
        
        return redirect()->route('admin.properties')->with('success', 'Property updated successfully!');
    }

    /**
     * Toggle property status
     */
    public function togglePropertyStatus($id)
    {
        $property = RsProperty::findOrFail($id);
        $property->active = !$property->active;
        $property->save();
        
        return response()->json([
            'success' => true, 
            'message' => 'Property status updated successfully',
            'active' => $property->active
        ]);
    }

    /**
     * Delete property
     */
    public function deleteProperty($id)
    {
        $property = RsProperty::findOrFail($id);
        RsPropertyImage::where('property_id', $id)->delete();
        $property->delete();
        
        return response()->json(['success' => true, 'message' => 'Property deleted successfully']);
    }

    /**
     * Show all reservations
     */
    public function reservations(Request $request)
    {
        $query = RsReservation::with(['property', 'customer']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('confirmation_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('property', function($pq) use ($search) {
                      $pq->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('date_start', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('date_end', '<=', $request->date_to);
        }
        
        $reservations = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $statuses = [
            1 => 'Pending',
            2 => 'Confirmed',
            3 => 'Cancelled',
            4 => 'Completed',
            5 => 'Checked In',
            6 => 'Checked Out',
        ];
        
        return view('admin.reservations', compact('reservations', 'statuses'));
    }

    /**
     * Show reservation details
     */
    public function reservationDetails($id)
    {
        $reservation = RsReservation::with(['property.owner', 'property.images', 'customer'])->findOrFail($id);
        return view('admin.reservation-details', compact('reservation'));
    }

    /**
     * Update reservation status
     */
    public function updateReservationStatus(Request $request, $id)
    {
        $reservation = RsReservation::findOrFail($id);
        $reservation->status = $request->status;
        $reservation->save();
        
        return response()->json(['success' => true, 'message' => 'Reservation status updated successfully']);
    }

    /**
     * Cancel reservation
     */
    public function cancelReservation($id)
    {
        $reservation = RsReservation::findOrFail($id);
        $reservation->status = 3;
        $reservation->save();
        
        return response()->json(['success' => true, 'message' => 'Reservation cancelled successfully']);
    }

    /**
     * Show financial reports
     */
    public function finances(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->start_date : date('Y-m-01');
        $endDate = $request->filled('end_date') ? $request->end_date : date('Y-m-d');
        
        $totalRevenue = RsReservation::where('active', true)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('total_price');
        
        $totalBookings = RsReservation::where('active', true)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->count();
        
        $avgBookingValue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
        
        $totalCommissions = RsReservation::where('active', true)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('broker_cut');
        
        $revenueByProperty = RsReservation::with('property')
            ->where('active', true)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('property_id', DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->groupBy('property_id')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
        
        $topOwners = RsReservation::with('property.owner')
            ->where('rs_reservations.active', true)
            ->whereBetween('rs_reservations.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->join('rs_properties', 'rs_reservations.property_id', '=', 'rs_properties.id')
            ->select('rs_properties.owner_id', DB::raw('SUM(rs_reservations.total_price) as revenue'))
            ->groupBy('rs_properties.owner_id')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();
        
        return view('admin.finances', compact(
            'totalRevenue',
            'totalBookings',
            'avgBookingValue',
            'totalCommissions',
            'revenueByProperty',
            'topOwners',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show system settings
     */
    public function settings()
    {
        $settings = [
            'site_name' => 'IVR Reservation System',
            'site_email' => 'admin@ivrreservation.com',
            'commission_rate' => 10,
            'tax_rate' => 8.5,
        ];
        return view('admin.settings', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        // Handle settings update
        return response()->json(['success' => true, 'message' => 'Settings updated successfully']);
    }

    /**
     * Get dashboard statistics (API)
     */
    public function getStats()
    {
        return response()->json([
            'users' => RsUser::count(),
            'properties' => RsProperty::count(),
            'reservations' => RsReservation::count(),
            'revenue' => RsReservation::where('active', true)->sum('total_price'),
        ]);
    }

    /**
     * Show analytics page with real data
     */
    public function analytics(Request $request)
    {
        // Get date range
        $days = $request->input('days', 30);
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();
        
        // Real statistics from database
        $totalUsers = RsUser::count();
        $newUsers = RsUser::where('created_at', '>=', $startDate)->count();
        $totalProperties = RsProperty::count();
        $activeProperties = RsProperty::where('active', true)->count();
        $totalReservations = RsReservation::count();
        $periodReservations = RsReservation::where('created_at', '>=', $startDate)->count();
        $totalRevenue = RsReservation::where('active', true)->sum('total_price');
        $periodRevenue = RsReservation::where('active', true)
            ->where('created_at', '>=', $startDate)
            ->sum('total_price');
        
        // Top properties by reservations
        $topProperties = RsProperty::withCount(['reservations' => function($query) use ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }])
        ->with('owner')
        ->where('active', true)
        ->orderBy('reservations_count', 'desc')
        ->limit(10)
        ->get();
        
        // Reservations by status
        $reservationsByStatus = [
            ['status' => 'Pending', 'count' => RsReservation::where('status', 1)->count(), 'color' => '#F59E0B'],
            ['status' => 'Confirmed', 'count' => RsReservation::where('status', 2)->count(), 'color' => '#10B981'],
            ['status' => 'Cancelled', 'count' => RsReservation::where('status', 3)->count(), 'color' => '#EF4444'],
            ['status' => 'Completed', 'count' => RsReservation::where('status', 4)->count(), 'color' => '#3B82F6'],
        ];
        
        // Daily reservation data for chart
        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayStart = now()->subDays($i)->startOfDay();
            $dayEnd = now()->subDays($i)->endOfDay();
            
            $dailyData[] = [
                'date' => now()->subDays($i)->format('M d'),
                'reservations' => RsReservation::whereBetween('created_at', [$dayStart, $dayEnd])->count(),
                'revenue' => RsReservation::where('active', true)->whereBetween('created_at', [$dayStart, $dayEnd])->sum('total_price'),
            ];
        }
        
        // Monthly revenue for last 12 months
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => RsReservation::where('active', true)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->sum('total_price'),
                'bookings' => RsReservation::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }
        
        // Top locations
        $topLocations = RsProperty::select('city', 'state', DB::raw('COUNT(*) as property_count'))
            ->whereNotNull('city')
            ->where('active', true)
            ->groupBy('city', 'state')
            ->orderBy('property_count', 'desc')
            ->limit(5)
            ->get();
        
        // User growth data
        $userGrowth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthEnd = $month->copy()->endOfMonth();
            $userGrowth[] = [
                'month' => $month->format('M'),
                'users' => RsUser::where('created_at', '<=', $monthEnd)->count(),
            ];
        }
        
        // Calculate conversion rate (reservations / users * 100)
        $conversionRate = $totalUsers > 0 ? round(($totalReservations / $totalUsers) * 100, 2) : 0;
        
        // Average booking value
        $avgBookingValue = $totalReservations > 0 ? round($totalRevenue / $totalReservations, 2) : 0;
        
        return view('admin.analytics', compact(
            'totalUsers',
            'newUsers',
            'totalProperties',
            'activeProperties',
            'totalReservations',
            'periodReservations',
            'totalRevenue',
            'periodRevenue',
            'topProperties',
            'reservationsByStatus',
            'dailyData',
            'monthlyRevenue',
            'topLocations',
            'userGrowth',
            'conversionRate',
            'avgBookingValue',
            'days'
        ));
    }

    /**
     * Export data to CSV
     */
    public function exportData(Request $request)
    {
        $type = $request->type ?? 'users';
        
        switch ($type) {
            case 'users':
                $data = RsUser::all();
                break;
            case 'properties':
                $data = RsProperty::with('owner')->get();
                break;
            case 'reservations':
                $data = RsReservation::with(['property', 'customer'])->get();
                break;
            default:
                return response()->json(['error' => 'Invalid export type'], 400);
        }
        
        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Bulk action handler
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'type' => 'required|in:users,properties,reservations',
            'ids' => 'required|array',
        ]);
        
        $model = match($request->type) {
            'users' => RsUser::class,
            'properties' => RsProperty::class,
            'reservations' => RsReservation::class,
        };
        
        $items = $model::whereIn('id', $request->ids);
        
        switch ($request->action) {
            case 'delete':
                if ($request->type === 'users') {
                    $items->where('id', '!=', Auth::id());
                }
                $items->delete();
                break;
            case 'activate':
                $items->update(['active' => true]);
                break;
            case 'deactivate':
                $items->update(['active' => false]);
                break;
        }
        
        return response()->json(['success' => true, 'message' => 'Bulk action completed successfully']);
    }

    /**
     * Activity log
     */
    public function activityLog(Request $request)
    {
        // This would normally fetch from an activity log table
        // For now, we'll show recent users and reservations as activity
        $activities = collect();
        
        $recentUsers = RsUser::orderBy('created_at', 'desc')->limit(10)->get();
        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user_created',
                'message' => "New user registered: {$user->first_name} {$user->last_name}",
                'created_at' => $user->created_at,
            ]);
        }
        
        $recentReservations = RsReservation::with(['property', 'customer'])->orderBy('created_at', 'desc')->limit(10)->get();
        foreach ($recentReservations as $res) {
            $activities->push([
                'type' => 'reservation_created',
                'message' => "New reservation: " . ($res->property->title ?? 'Unknown Property'),
                'created_at' => $res->created_at,
            ]);
        }
        
        $activities = $activities->sortByDesc('created_at')->take(20)->values();
        
        return view('admin.activity-log', compact('activities'));
    }
}
