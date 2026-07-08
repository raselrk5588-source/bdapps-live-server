<?php

namespace App\Http\Controllers\API;

use App\Http\Components\BDAppsApi;
use App\Http\Components\MoUssdReceiver;
use App\Http\Controllers\Controller;
use App\InstallApp;
use App\SubscriptionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UssdSubscriptionController extends Controller
{
    public function __construct()
    {
        $this->receiver=new MoUssdReceiver;
    }
    public function index()
    {
        $this->receiver->getAddress();
//        Log::info('Log message', array('context' => 'Other helpful information'));
        if($this->receiver->getUssdOperation()=="mo-init")
        {
            $appId = $this->receiver->getApplicationId();
            $app_pass = InstallApp::where('app_id', $appId)->first();
            if($app_pass) {
                $api = new BDAppsApi;
                $api->app_id = $appId;
                $api->password = $app_pass->password;
                $api->destinationAddress = $this->receiver->getAddress();
                $api->sessionId = $this->receiver->getSessionId();
                $api->ussdOperation = "mt-fin";
                $api->version=$this->receiver->getVersion();
                $api->subscriberId =$this->receiver->getAddress();
                $check_status=$api->getstatus();
                $response="";
                $check_status=json_decode($check_status);
                if($check_status->subscriptionStatus=="REGISTERED")
                {
                    $api->ussdresposemessage="You already register";
                    $response=$api->ussdSend();
                }else{
                    $api->ussdresposemessage="You will get a confirmation sms.";
                    $response=$api->ussdSend();
                    $api->subscribe();
                    $subscribe=new SubscriptionData;
                    $subscribe->app_id=$this->receiver->getApplicationId();
                    $subscribe->subscribe_id=$this->receiver->getAddress();
                    $subscribe->save();
                }

                return  $response;
            }
        }
    }
}
