<?php

namespace App\Http\Controllers\API;

use App\Http\Components\BDAppsApi;
use App\Http\Controllers\Controller;
use App\InstallApp;
use App\SubscriptionData;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function requestOtp(Request $request)
    {
        $data = $request->validate([
            'app_id' => ['required', 'string'],
            'subscriber_id' => ['required', 'string'],
            'application_hash' => ['nullable', 'string'],
            'application_meta_data' => ['nullable', 'array'],
        ]);

        $installApp = InstallApp::where('app_id', $data['app_id'])->first();
        if (!$installApp) {
            return response()->json([
                'statusCode' => 'E4040',
                'statusDetail' => 'Application not found.',
            ], 404);
        }

        $api = new BDAppsApi;
        $api->app_id = $installApp->app_id;
        $api->password = $installApp->password;
        $api->subscriberId = $data['subscriber_id'];
        $api->applicationHash = $data['application_hash'] ?? '';
        $api->applicationMetaData = $data['application_meta_data'] ?? [];

        return $this->forwardBdAppsResponse($api->otpRequest());
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'app_id' => ['required', 'string'],
            'reference_no' => ['required', 'string'],
            'otp' => ['required', 'string'],
        ]);

        $installApp = InstallApp::where('app_id', $data['app_id'])->first();
        if (!$installApp) {
            return response()->json([
                'statusCode' => 'E4040',
                'statusDetail' => 'Application not found.',
            ], 404);
        }

        $api = new BDAppsApi;
        $api->app_id = $installApp->app_id;
        $api->password = $installApp->password;
        $api->referenceNo = $data['reference_no'];
        $api->otp = $data['otp'];

        $rawResponse = $api->otpVerify();
        $payload = $this->decodeJsonResponse($rawResponse);
        if ($payload === null) {
            return response()->json([
                'statusCode' => 'E5020',
                'statusDetail' => 'Invalid response received from BDApps.',
            ], 502);
        }

        $statusCode = strtoupper((string) ($payload['statusCode'] ?? ''));
        $subscriptionStatus = strtoupper((string) ($payload['subscriptionStatus'] ?? ''));
        $subscriberId = $payload['subscriberId'] ?? null;

        if ($statusCode === 'S1000' && $subscriptionStatus === 'REGISTERED' && $subscriberId) {
            SubscriptionData::updateOrCreate(
                [
                    'app_id' => $installApp->app_id,
                    'subscribe_id' => $subscriberId,
                ],
                []
            );
        } elseif (in_array($subscriptionStatus, ['UNREGISTERED', 'UNSUBSCRIBED', 'DEREGISTERED'], true) && $subscriberId) {
            SubscriptionData::where('app_id', $installApp->app_id)
                ->where('subscribe_id', $subscriberId)
                ->delete();
        }

        return response()->json($payload);
    }

    private function forwardBdAppsResponse($rawResponse)
    {
        $payload = $this->decodeJsonResponse($rawResponse);
        if ($payload === null) {
            return response()->json([
                'statusCode' => 'E5020',
                'statusDetail' => 'Invalid response received from BDApps.',
            ], 502);
        }

        return response()->json($payload);
    }

    private function decodeJsonResponse($rawResponse)
    {
        if (!is_string($rawResponse) || trim($rawResponse) === '') {
            return null;
        }

        $decoded = json_decode($rawResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return null;
        }

        return $decoded;
    }
}
