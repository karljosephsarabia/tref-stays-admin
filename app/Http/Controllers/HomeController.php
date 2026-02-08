<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SMD\Common\ReservationSystem\Models\RsReservation;
use SMD\Common\ReservationSystem\Models\RsProperty;
use SMD\Common\ReservationSystem\Enums\RoleType;

class HomeController extends AppBaseController
{
    public function __construct()
    {
        //$this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->user()) {
            $user = $request->user();

            // For owners, redirect to owner dashboard
            if ($user->role_id == RoleType::OWNER) {
                return redirect()->route('owner.dashboard');
            }

            // For renters, redirect to renter dashboard
            if ($user->role_id == RoleType::RENTER) {
                return redirect()->route('renter.dashboard');
            }

            // Default fallback for other roles
            return redirect()->route('renter.dashboard');
        }

        return redirect()->route('login');
    }

    public function setting()
    {
        return view('settings');
    }

}