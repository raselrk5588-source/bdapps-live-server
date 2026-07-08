<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\InstallApp;
use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu', "dashboard");
            Session::put('sub_menu', "dashboard");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['install_app'] = InstallApp::where("user_id", Auth::user()->id)->get()->count();
        $this->data['total_left'] = config('app.total_install_limit') - $this->data['install_app'];
        $install_app = new InstallApp;
        $this->data['total_apps'] = $install_app->get_app_details();
        //        dd($data);
        return view("user.dashboard.dashboard", $this->data);
    }
}