<?php

namespace App\Http\Controllers\API;

use App\Http\Components\BDAppsApi;
use App\Http\Components\MoUssdReceiver;
use App\Http\Controllers\Controller;
use App\InstallApp;
use App\SubscriptionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UssdSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        Log::info('--- BDApps USSD Webhook Started ---');
        Log::info('Incoming Data:', $request->all());

        // Initialize receiver with Laravel's parsed request data
        $this->receiver = new MoUssdReceiver($request->all());

        if (!$this->receiver->getAddress()) {
            Log::warning('USSD Failed: Address missing in request');
            return response()->json([
                'statusCode' => 'E1312',
                'statusDetail' => 'Request Invalid.',
            ]);
        }

        $appId = $this->receiver->getApplicationId();
        $sessionId = $this->receiver->getSessionId();
        $sourceAddress = $this->receiver->getAddress();

        if($this->receiver->getUssdOperation() == "mo-init")
        {
            Log::info("Looking for App ID in DB: " . $appId);
            $app_pass = InstallApp::where('app_id', $appId)->first();
            
            if($app_pass) {
                Log::info("App found in DB. Proceeding with BDApps API...");
                $api = new BDAppsApi;
                $api->app_id = $appId;
                $api->password = $app_pass->password;
                $api->subscriberId = $sourceAddress;
                
                $check_status = $api->getstatus();
                Log::info("BDApps getstatus API Response:", ['response' => $check_status]);
                $check_status_decoded = json_decode($check_status);
                
                $responseMessage = "";

                if($check_status_decoded && isset($check_status_decoded->subscriptionStatus) && $check_status_decoded->subscriptionStatus=="REGISTERED")
                {
                    Log::info("User is already registered.");
                    $responseMessage = "You already register";
                } else {
                    Log::info("User not registered. Subscribing...");
                    $responseMessage = "You will get a confirmation sms.";
                    
                    // Trigger subscription in background
                    $api->version = $this->receiver->getVersion();
                    $sub_res = $api->subscribe();
                    Log::info("BDApps subscribe Response:", ['response' => $sub_res]);
                    
                    $subscribe = new SubscriptionData;
                    $subscribe->app_id = $appId;
                    $subscribe->subscribe_id = $sourceAddress;
                    $subscribe->save();
                    Log::info("Saved to SubscriptionData DB.");
                    
                    // If BDApps requires double confirmation (E1338), we must NOT send mt-fin,
                    // otherwise BDApps throws 'Invalid Response'.
                    if (is_string($sub_res) || is_array($sub_res)) {
                        $sub_str = is_array($sub_res) ? json_encode($sub_res) : $sub_res;
                        if (strpos($sub_str, 'E1338') !== false || strpos($sub_str, 'PENDING CONFIRMATION') !== false) {
                            Log::info("Double Opt-In Triggered (E1338). Returning simple success to allow BDApps confirmation menu.");
                            return response()->json([
                                "statusCode" => "S1000",
                                "statusDetail" => "Success"
                            ]);
                        }
                    }
                }

                Log::info('--- BDApps USSD Webhook Ended ---');
                
                // Return SYNCHRONOUS USSD response
                return response()->json([
                    "message" => $responseMessage,
                    "destinationAddress" => $sourceAddress,
                    "sessionId" => $sessionId,
                    "ussdOperation" => "mt-fin",
                    "encoding" => $this->receiver->getEncoding() ? $this->receiver->getEncoding() : "440",
                    "version" => "1.0"
                ]);

            } else {
                Log::error("App ID NOT FOUND in database: " . $appId);
                return response()->json([
                    "statusCode" => "S1000",
                    "statusDetail" => "Success"
                ]);
            }
        } else {
            Log::info("Operation is not mo-init. It is: " . $this->receiver->getUssdOperation());
            
            $app_pass = InstallApp::where('app_id', $appId)->first();
            $pass = $app_pass ? $app_pass->password : "";

            // Handle mo-cont safely by closing the session
            return response()->json([
                "message" => "Thank you for using our service.",
                "destinationAddress" => $sourceAddress,
                "sessionId" => $sessionId,
                "ussdOperation" => "mt-fin",
                "encoding" => $this->receiver->getEncoding() ? $this->receiver->getEncoding() : "440",
                "version" => "1.0"
            ]);
        }
    }
}
