<?php

namespace App\Http\Components;

use InvalidArgumentException;

class MoSmsReceiver
{
    private $sourceAddress;
    private $message;
    private $requestId;
    private $applicationId;
    private $encoding;
    private $version;

    public function __construct(array $payload)
    {
        $this->sourceAddress = $payload['sourceAddress'] ?? null;
        $this->message = $payload['message'] ?? null;
        $this->requestId = $payload['requestId'] ?? null;
        $this->applicationId = $payload['applicationId'] ?? null;
        $this->encoding = $payload['encoding'] ?? null;
        $this->version = $payload['version'] ?? null;

        if (!$this->sourceAddress || $this->message === null) {
            throw new InvalidArgumentException('Some of the required parameters are not provided');
        }
    }

    public function getAddress()
    {
        return $this->sourceAddress;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRequestID()
    {
        return $this->requestId;
    }

    public function getApplicationId()
    {
        return $this->applicationId;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function getVersion()
    {
        return $this->version;
    }
}
