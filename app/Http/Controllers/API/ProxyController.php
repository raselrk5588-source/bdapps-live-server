<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\InstallApp;
use Illuminate\Http\Request;

class ProxyController extends Controller
{
    public function forward(Request $request)
    {
        // Get JSON body as array
        $data = $request->json()->all();
        
        $appId = $data['applicationId'] ?? ($data['app_id'] ?? null);
        $targetUrl = $request->header('X-Target-URL');

        if (!$appId || !$targetUrl) {
            return response()->json([
                'statusCode' => 'E1312',
                'statusDetail' => 'Missing applicationId or X-Target-URL header in request'
            ], 400);
        }

        // Fetch password from DB
        $app_info = InstallApp::where("app_id", $appId)->first();
        if (!$app_info) {
            return response()->json([
                'statusCode' => 'E1312',
                'statusDetail' => 'App ID not found in the system.'
            ], 404);
        }

        // Inject/Replace the correct password
        $data['password'] = $app_info->password;
        $data['applicationId'] = $appId;

        // Forward the request to BDApps
        $ch = curl_init($targetUrl);
        
        $jsonStream = json_encode($data);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonStream)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStream);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return response($response, $httpCode)->header('Content-Type', 'application/json');
    }
}
