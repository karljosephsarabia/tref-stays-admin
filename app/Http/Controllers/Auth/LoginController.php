<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RsUser;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use SMD\Common\ReservationSystem\Enums\RoleType;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Login phoneNumber to be used by the controller.
     *
     * @var string
     */
    protected $phoneNumber;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->phoneNumber = $this->findPhoneNumber();
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = $request->email;
        $password = $request->password;
        $user = null;

        // Try email login with bcrypt password
        $user = RsUser::where('email', $loginField)->where('activated', true)->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // Try phone number login with bcrypt password
        $user = RsUser::where('phone_number', $loginField)->where('activated', true)->first();
        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // Legacy: try PIN login for old accounts (email)
        $user = RsUser::where('email', $loginField)->where('pin', $password)->where('activated', true)->first();
        if ($user) {
            Auth::login($user, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // Legacy: try PIN login for old accounts (phone)
        $user = RsUser::where('phone_number', $loginField)->where('pin', $password)->where('activated', true)->first();
        if ($user) {
            Auth::login($user, $request->filled('remember'));
            return $this->sendLoginResponse($request);
        }

        // Login failed
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->phoneNumber), FILTER_VALIDATE_EMAIL)
            ? $this->phoneNumber
            : 'phone_number';

        return [
            $field => $request->get($this->phoneNumber),
            'password' => $request->password
        ];
    }

    /**
     * Get the login phoneNumber to be used by the controller.
     *
     * @return string
     */
    public function findPhoneNumber()
    {
        $login = request()->input('email');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect admins to their dashboard (role_id = 1)
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect brokers to admin dashboard (role_id = 2)
        if ($user->role_id == 2) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect owners to their dashboard (role_id = 3)
        if ($user->role_id == 3) {
            return redirect()->route('owner.dashboard');
        }

        // Redirect renters to their dashboard (role_id = 5)
        if ($user->role_id == 5) {
            return redirect()->route('renter.dashboard');
        }

        return redirect()->intended($this->redirectPath());
    }
}
