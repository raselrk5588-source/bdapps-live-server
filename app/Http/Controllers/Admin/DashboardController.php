<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Session;
class DashboardController extends Controller
{
    public function __construct()
    {
         $this->middleware(function ($request, $next) {
            Session::put('top_menu',"dashboard");
            Session::put('sub_menu',"dashboard");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['total_active_user']=User::where("role_id",2)->Active()->count();
        $this->data['total_pending_user']=User::where("role_id",2)->Pending()->count();
        $this->data['new_register_user']=User::where("role_id",2)->New()->count();
        return view("admin.dashboard.dashboard",$this->data);
    }
}
