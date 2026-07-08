<?php

namespace App\Http\Controllers\API;

use App\Http\Components\BDAppsApi;
use App\Http\Components\MoSmsReceiver;
use App\Http\Components\SmsSender;
use App\Http\Controllers\Controller;
use App\InstallApp;
use App\SubscriptionData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SMSSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $receiver = new MoSmsReceiver($request->json()->all());
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'statusCode' => 'E1312',
                'statusDetail' => $exception->getMessage(),
            ], 422);
        }

        $message = trim($receiver->getMessage());
        $parts = preg_split('/\s+/', $message);
        $action = strtoupper($parts[0] ?? '');
        $keyword = strtoupper($parts[1] ?? '');

        $installApp = InstallApp::where('app_id', $receiver->getApplicationId())->first();

        if (!$installApp && $keyword !== '') {
            $installApp = InstallApp::whereRaw('UPPER(sms_keyword) = ?', [$keyword])->first();
        }

        if (!$installApp) {
            return $this->acknowledgeAndRespond(
                'App keyword not found.',
                $receiver->getAddress()
            );
        }

        $api = new BDAppsApi;
        $api->app_id = $installApp->app_id;
        $api->password = $installApp->password;
        $api->subscriberId = $receiver->getAddress();
        $api->version = $receiver->getVersion() ?: '1.0';

        if ($action === 'START') {
            $statusResponse = json_decode($api->getstatus());
            $status = $statusResponse->subscriptionStatus ?? null;

            if ($status === 'REGISTERED') {
                $reply = 'You are already subscribed to ' . $installApp->app_name . '.';
            } else {
                $api->subscribe();

                $subscription = SubscriptionData::where('app_id', $installApp->app_id)
                    ->where('subscribe_id', $receiver->getAddress())
                    ->first();

                if (!$subscription) {
                    $subscription = new SubscriptionData;
                    $subscription->app_id = $installApp->app_id;
                    $subscription->subscribe_id = $receiver->getAddress();
                    $subscription->save();
                }
                $reply = 'Subscription request processed for ' . $installApp->app_name . '.';
            }

            return $this->acknowledgeAndRespond($reply, $receiver->getAddress(), $installApp);
        }

        if ($action === 'STOP') {
            $api->unSubscribe();

            SubscriptionData::where('app_id', $installApp->app_id)
                ->where('subscribe_id', $receiver->getAddress())
                ->delete();

            return $this->acknowledgeAndRespond(
                'Unsubscription request processed for ' . $installApp->app_name . '.',
                $receiver->getAddress(),
                $installApp
            );
        }

        return $this->acknowledgeAndRespond(
            'Invalid SMS format. Use START ' . Str::upper($installApp->sms_keyword) . ' or STOP ' . Str::upper($installApp->sms_keyword) . '.',
            $receiver->getAddress(),
            $installApp
        );
    }

    private function acknowledgeAndRespond($replyMessage, $destinationAddress, InstallApp $installApp = null)
    {
        if ($installApp) {
            $sender = new SmsSender('https://developer.bdapps.com/sms/send', $installApp->app_id, $installApp->password);
            $sender->sms(strip_tags($replyMessage), [$destinationAddress]);
        }

        return response()->json([
            'statusCode' => 'S1000',
            'statusDetail' => 'Success',
        ]);
    }
}
