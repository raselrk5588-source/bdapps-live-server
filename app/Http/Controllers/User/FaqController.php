<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\InstallApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use PDF;
class FaqController extends Controller
{
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            Session::put('top_menu',"faq");
            Session::put('sub_menu',"faq");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['app_name']=InstallApp::where("user_id",Auth::user()->id)->get();
        return view("user.faq.faq",$this->data);
    }

    public function faqGenerator(Request $request)
    {
        $app_id=$request->input("app_id");
        $app_info=InstallApp::where("id",$app_id)->first();
        $data=$request->validate([
            'sms_keyword' => ['required', 'string'],
            'ussd_code' => ['required', 'string'],
            'long_desc' => ['required', 'string'],
            'short_desc' => ['required', 'string'],
       ]);
        $data['app_name']=$app_info->app_name;
        $data['app_id']=$app_info->app_id;
        $pdf = PDF::loadView('user.faq.faqgenerate', $data)->setWarnings(false)->setPaper('a4', 'portrait');
        return  $pdf->download($data['app_name'].'.pdf');
    }
}
