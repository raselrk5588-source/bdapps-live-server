<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\InstallApp;
use App\SubscriptionData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionNotificationController extends Controller
{
    public function index(Request $request)
    {
        $payload = $request->json()->all();

        Log::info('BDApps subscription notification received', [
            'payload' => $payload,
        ]);

        $applicationId = $payload['applicationId'] ?? null;
        $subscriberId = $payload['subscriberId'] ?? null;
        $status = strtoupper($payload['status'] ?? '');

        if ($applicationId && $subscriberId) {
            $installApp = InstallApp::where('app_id', $applicationId)->first();

            if ($installApp) {
                if ($status === 'REGISTERED') {
                    $subscription = SubscriptionData::where('app_id', $applicationId)
                        ->where('subscribe_id', $subscriberId)
                        ->first();

                    if (!$subscription) {
                        $subscription = new SubscriptionData;
                        $subscription->app_id = $applicationId;
                        $subscription->subscribe_id = $subscriberId;
                        $subscription->save();
                    }
                } elseif (in_array($status, ['UNREGISTERED', 'UNSUBSCRIBED', 'DEREGISTERED'], true)) {
                    SubscriptionData::where('app_id', $applicationId)
                        ->where('subscribe_id', $subscriberId)
                        ->delete();
                }
            }
        }

        return response()->json([
            'statusCode' => 'S1000',
            'statusDetail' => 'Success',
        ]);
    }
}
