<?php

namespace App\Http\Components;

class BDAppsApi {
    private $url;
    public $app_id = "";
    public $password = "";
    public $subscriberId = "";
    public $ussdresposemessage = "";
    private $status = false;
    private $message = "Invalid Request";
    private $statusCode = "E1312"; // BDAPPS ERROR CODE // FOR SUCCESS CODE: "S1000"
    private $statusDetail = "Request Invalid.";
    public $destinationAddress='';
    public $sessionId;
    public $ussdOperation;
    public $version;
    public $applicationHash = "";
    public $applicationMetaData = [];
    public $referenceNo = "";
    public $otp = "";

    public function isInvalid() {
        $invalid = false;
        $message = "";
        if(!isset($this->app_id)) {
            $message .= " AppId not found!";
            $invalid = true;
        }
        if(!isset($this->password)) {
            $message .= " Password not found!";
            $invalid = true;
        }
//        if(!isset($this->subscriberId)) {
//            $message .= " SubscriberId not found!";
//            $invalid = true;
//        }
        $this->message = $message;
        return $invalid;
    }

    public function errorOutput() {
        return json_encode([
            'statusCode' => $this->statusCode,
            'statusDetail' => $this->statusDetail,
        ]);
    }
    public function ussdSend() {
        if($this->isInvalid()) {
            return $this->errorOutput();
        }
         $arrayField = array(
             "applicationId" => $this->app_id,
            "password" => $this->password,
            "message" => $this->ussdresposemessage,
            "sessionId" => $this->sessionId,
            "ussdOperation" => $this->ussdOperation,
            "destinationAddress" => $this->destinationAddress,
            );
        $json = json_encode($arrayField);
        $this->url = 'https://developer.bdapps.com/ussd/send';
        return $this->sendRequest($json);
    }

    public function subscribe() {
        if($this->isInvalid()) {
            return $this->errorOutput();
        }
         $arrayField = array(
             "applicationId" => $this->app_id,
            "password" => $this->password,
            "version" => $this->version,
            "action" => "1",
            "subscriberId" => $this->subscriberId
            );
        $json=json_encode($arrayField);

        $this->url = 'https://developer.bdapps.com/subscription/send';
        return $this->sendRequest($json);
    }

    public function unSubscribe(){
        if($this->isInvalid()) {
            return $this->errorOutput();
        }
       $arrayField = array(
             "applicationId" => $this->app_id,
            "password" => $this->password,
            "version" => $this->version,
            "action" => "0",
            "subscriberId" => $this->subscriberId
            );
        $json=json_encode($arrayField);

        $this->url = 'https://developer.bdapps.com/subscription/send';
        return $this->sendRequest($json);
    }

    public function getstatus(){
        if($this->isInvalid()) {
            return $this->errorOutput();
        }
       $arrayField = array(
         "applicationId" => $this->app_id,
        "password" => $this->password,
        "subscriberId" => $this->subscriberId
        );


        $json = json_encode($arrayField);
        $this->url = 'https://developer.bdapps.com/subscription/getstatus';
        return $this->sendRequest($json);
    }

    public function otpRequest()
    {
        if ($this->isInvalid()) {
            return $this->errorOutput();
        }

        if (!$this->hasValue($this->subscriberId)) {
            return $this->errorOutput();
        }

        $arrayField = array(
            "applicationId" => $this->app_id,
            "password" => $this->password,
            "subscriberId" => $this->subscriberId,
        );

        if ($this->hasValue($this->applicationHash)) {
            $arrayField["applicationHash"] = $this->applicationHash;
        }

        if (is_array($this->applicationMetaData) && !empty($this->applicationMetaData)) {
            $arrayField["applicationMetaData"] = $this->applicationMetaData;
        }

        $json = json_encode($arrayField);
        $this->url = 'https://developer.bdapps.com/otp/request';
        return $this->sendRequest($json);
    }

    public function otpVerify()
    {
        if ($this->isInvalid()) {
            return $this->errorOutput();
        }

        if (!$this->hasValue($this->referenceNo) || !$this->hasValue($this->otp)) {
            return $this->errorOutput();
        }

        $arrayField = array(
            "applicationId" => $this->app_id,
            "password" => $this->password,
            "referenceNo" => $this->referenceNo,
            "otp" => $this->otp,
        );

        $json = json_encode($arrayField);
        $this->url = 'https://developer.bdapps.com/otp/verify';
        return $this->sendRequest($json);
    }

    private function hasValue($value)
    {
        return isset($value) && $value !== '';
    }

    public function sendRequest($jsonStream){

         $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStream);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch); //Send request and get response
        curl_close($ch);
        return $res;

    }

}
