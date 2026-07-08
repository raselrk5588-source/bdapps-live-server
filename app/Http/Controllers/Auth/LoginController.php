<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo;

    protected function redirectTo()
    {
        if ((int) Auth::user()->role_id === 1) {
            return route('admin.dashboard');

        }
        elseif ((int) Auth::user()->role_id === 2) {
            return route('user.dashboard');
        }else{
            return route('user.dashboard');
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('login.login');
    }

    public function username()
    {
        return 'phone';
    }
    protected function credentials(Request $request)
    {
        return ['phone'=>$request->{$this->username()},'password'=>$request->password,'status'=>1];
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('phone', $request->{$this->username()})->first();
        $message = trans('auth.failed');

        if ($user) {
            if ((int) $user->status === 0) {
                $message = 'Wait for approval.';
            } elseif ((int) $user->status === 2) {
                $message = 'Account is pending status.';
            }
        }

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }
}
