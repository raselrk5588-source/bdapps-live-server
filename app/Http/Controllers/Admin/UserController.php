<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\University;
use App\User;
use App\InstallApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu', "user");
            Session::put('sub_menu', "user");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['users'] = User::where("role_id", 1)->get();
        $this->data['university'] = University::orderBy("name", "asc")->get();
        return view("admin.users.users", $this->data);
    }

    public function view()
    {
        $search_key = request()->input("search_key");
        $filter_by = request()->input("filter_by");
        $approved_by = request()->input("approved_by");
        $university_id = request()->input("university_id");
        $users = User::with("approver")->with("university")->orderBy("id", "desc")
            ->where("role_id", 2);
        if ($filter_by != '') {
            $users = $users->where('status', $filter_by);
        }
        if ($approved_by != '') {
            $users = $users->where('approved_by', $approved_by);
        }
        if ($university_id != '') {
            $users = $users->where('university_id', $university_id);
        }
        if ($search_key != '') {
            $users = $users->where('email', 'like', '%' . $search_key . '%');
            $users = $users->orWhere('phone', 'like', '%' . $search_key . '%');
        }
        $users = $users->paginate(10);
        $this->data['users'] = $users;
        $page = request()->input('page');
        if ($page <= 1) {
            $this->data['sl_counter'] = 1;
        } else {
            $this->data['sl_counter'] = $page * 10 - 9;
        }
        $returnHTML = view('admin.users.users_data')->with($this->data)->render();
        return response()->json(array('success' => true, 'html' => $returnHTML));
        //        dd(request()->all());
    }

    public function control($user_id)
    {
        $user = User::where("id", $user_id)->first();
        if ($user->status == 1) {
            $user->status = 2;
        } else if ($user->status == 0) {
            $user->status = 1;
            $user->approved_by = Auth::id();
        } else if ($user->status == 2) {
            $user->status = 1;
        }
        $user->save();
        setMessage("message", 'success', "User Update Successfully");
        return redirect()->route("admin.user");
    }

    public function userEdit($user_id)
    {
        $this->data['single'] = User::where("id", $user_id)->first();
        $this->data['university'] = University::orderBy("name", "asc")->get();
        return view('admin.users.user-edit', $this->data);
    }

    public function userUpdate($user_id)
    {
        $user = User::where("id", $user_id)->first();
        $user->name = request()->input("name");
        $user->phone = request()->input("phone");
        $user->email = request()->input("email");
        $user->university_id = request()->input("university_id");
        if ($this->phoneCheck($user_id)) {
            setMessage("message", "danger", "Phone already exits.");
            return back();
        }
        if ($this->emailCheck($user_id)) {
            setMessage("message", "danger", "Email already exits.");
            return back();
        }
        if (request()->input("password")) {
            $user->password = request()->input("password");
        }
        $user->save();
        setMessage("message", "success", "User Update Successfully");
        return back();
    }

    public function phoneCheck($user_id)
    {
        $phone = request()->input("phone");
        $where = ["phone" => $phone];
        return User::where($where)->where('id', '!=', $user_id)->first();
    }
    public function emailCheck($user_id)
    {
        $email = request()->input("email");
        $where = ["email" => $email];
        return User::where($where)->where('id', '!=', $user_id)->first();
    }

    public function login($user_id)
    {
        //        dd($user);
        Auth::loginUsingId($user_id);
        return redirect()->route("user.dashboard");
        //        dd($user);
    }

    public function userDelete($user_id)
    {
        $user = User::findOrFail($user_id);
        // Delete all apps for this user
        InstallApp::where('user_id', $user_id)->delete();
        // Delete user
        $user->delete();
        
        setMessage("message", "success", "User and associated apps deleted successfully.");
        return redirect()->route("admin.user");
    }

    public function appUpdate($app_id)
    {
        $app = InstallApp::findOrFail($app_id);
        if (request()->input("app_name")) $app->app_name = request()->input("app_name");
        if (request()->input("password")) $app->password = request()->input("password");
        if (request()->input("ussd_code")) $app->ussd_code = request()->input("ussd_code");
        if (request()->input("sms_keyword")) $app->sms_keyword = request()->input("sms_keyword");
        if (request()->input("sms_time")) $app->sms_time = request()->input("sms_time");
        if (request()->input("sms_time_format")) $app->sms_time_format = request()->input("sms_time_format");
        $app->save();

        setMessage("message", "success", "App details updated successfully.");
        return back();
    }
}