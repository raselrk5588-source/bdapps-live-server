<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class InstructionController extends Controller
{
    private $data;

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            Session::put('top_menu', "instruction");
            Session::put('sub_menu', "instruction");
            return $next($request);
        });
    }
    public function index()
    {
        $ip = gethostbyname($_SERVER['SERVER_NAME']);
        $this->data['host_address'] = $ip;
        $this->data['sms_url'] = config('app.bdapps_sms_url');
        $this->data['ussd_url'] = config('app.bdapps_ussd_url');
        $this->data['subscription_notify_url'] = config('app.bdapps_subscription_notify_url');
        return view("user.instruction.instruction", $this->data);
    }
}
