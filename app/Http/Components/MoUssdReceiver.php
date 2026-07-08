<?php


namespace App\Http\Components;


class MoUssdReceiver
{
    private $sourceAddress; // Define required parameters to receive response
    private $message;
    private $requestId;
    private $applicationId;
    private $encoding;
    private $version;
    private $sessionId;
    private $ussdOperation;
    private $vlrAddress;

    /*
        decode the json data an get them to an array
        Get data from Json objects
        check the validity of the response
    **/

    public function __construct($array = null){
        if ($array === null) {
            $array = json_decode(file_get_contents('php://input'), true);
        }
        
        if (is_array($array)) {
            $this->sourceAddress = isset($array['sourceAddress']) ? $array['sourceAddress'] : null;
            $this->message = isset($array['message']) ? $array['message'] : null;
            $this->requestId = isset($array['requestId']) ? $array['requestId'] : null;
            $this->applicationId = isset($array['applicationId']) ? $array['applicationId'] : null;
            $this->encoding = isset($array['encoding']) ? $array['encoding'] : null;
            $this->version = isset($array['version']) ? $array['version'] : null;
            $this->sessionId = isset($array['sessionId']) ? $array['sessionId'] : null;
            $this->ussdOperation = isset($array['ussdOperation']) ? $array['ussdOperation'] : null;
        }
    }

    /*
        Define getters to return receive data
    **/

    public function getAddress(){
        return $this->sourceAddress;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getRequestID(){
        return $this->requestId;
    }

    public function getApplicationId(){
        return $this->applicationId;
    }

    public function getEncoding(){
        return $this->encoding;
    }

    public function getVersion(){
        return $this->version;
    }

    public function getSessionId(){
        return $this->sessionId;
    }

    public function getUssdOperation(){
        return $this->ussdOperation;
    }
}
