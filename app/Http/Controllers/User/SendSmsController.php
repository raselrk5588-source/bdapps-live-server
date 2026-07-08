<?php

namespace App\Http\Controllers\User;

use App\Http\Components\SmsSender;
use App\Http\Controllers\Controller;
use App\InstallApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Session;
class SendSmsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu',"sendsms");
            Session::put('sub_menu',"sendsms");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['app_name']=InstallApp::where("user_id",Auth::user()->id)->get();
//        dd($this->data['app_name']);
        return view("user.sendsms.sendsms",$this->data);
    }

    public function sendSms(Request $request)
    {
        $url = "https://developer.bdapps.com/sms/send";
        $message = strip_tags($request->input('sms_body'));
        $app_info=InstallApp::where("id",$request->input('app_id'))->first();
        $app_id=$app_info->app_id;
        $password=$app_info->password;
        $sms_ob = new SmsSender($url, $app_id, $password);
        $response =  $sms_ob->broadcast($message);
        $response=json_decode($response);
        setMessage("message",'success',$response->statusDetail);
        return back();
    }

}
