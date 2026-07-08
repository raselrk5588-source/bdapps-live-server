<?php

namespace App\Http\Controllers\API;

use App\Http\Components\BDAppsApi;
use App\Http\Controllers\Controller;
use App\InstallApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriberController extends Controller
{
    public function subscribe(Request $request)
    {
        $subscriberId = $request->header("msisdn");

        if (!$subscriberId) {
            // return json_encode([
            //     'statusCode' => "E1312",
            //     'statusDetail' => "Please connect with Robi or Airtel number.",
            // ]);
        } else {
            $appId = $request->app_id;
            $app_pass = InstallApp::where('app_id', $appId)->first();
            if ($app_pass) {

                $api = new BDAppsApi;
                $api->app_id = $appId;
                $api->password = $app_pass->password;
                $api->subscriberId = $subscriberId;
                $api->subscribe();
            } else {

                Storage::put("log.txt", "not found");
            }
        }

        if (isset($request->file_path)) {
            return redirect($request->file_path);
        } else {
            echo "File missing!";
        }
    }
}