# BDApps OTP Integration Guide

## Purpose
Add support for BDApps TAP OTP flow in this Laravel project using:
- `POST https://developer.bdapps.com/otp/request`
- `POST https://developer.bdapps.com/otp/verify`

This document defines the API contract, expected payloads, Laravel-side design, and local data sync behavior.

## BDApps OTP APIs

### 1. OTP Request
Endpoint:
- `POST /otp/request` (BDApps host: `https://developer.bdapps.com`)

Required fields:
- `applicationId` (string)
- `password` (string)
- `subscriberId` (string, e.g. `tel:88018XXXXXXXX`)

Optional fields:
- `applicationHash` (string)
- `applicationMetaData` (object)

Sample request:
```json
{
  "applicationId": "APP_000375",
  "password": "a07118cda5215fc6d01db5b2ab848edd",
  "subscriberId": "tel:8801812345678",
  "applicationHash": "abcdefgh",
  "applicationMetaData": {
    "client": "MOBILEAPP",
    "device": "Samsung S10",
    "os": "android 8",
    "appCode": "https://play.google.com/store/apps/details?id=lk"
  }
}
```

Sample success response:
```json
{
  "version": "1.0",
  "statusCode": "S1000",
  "referenceNo": "213561321321613",
  "statusDetail": "Success"
}
```

### 2. OTP Verify
Endpoint:
- `POST /otp/verify` (BDApps host: `https://developer.bdapps.com`)

Required fields:
- `applicationId` (string)
- `password` (string)
- `referenceNo` (string)
- `otp` (string)

Sample request:
```json
{
  "applicationId": "APP_000375",
  "password": "a07118cda5215fc6d01db5b2ab848edd",
  "referenceNo": "213561321321613",
  "otp": "123564"
}
```

Sample success response:
```json
{
  "version": "1.0",
  "statusCode": "S1000",
  "subscriptionStatus": "REGISTERED",
  "statusDetail": "Success",
  "subscriberId": "tel:88018XXXXXXXX"
}
```

## Laravel Integration Design

## Components To Extend
- `app/Http/Components/BDAppsApi.php`

Add methods:
- `otpRequest()`
- `otpVerify()`

Suggested URLs:
- `https://developer.bdapps.com/otp/request`
- `https://developer.bdapps.com/otp/verify`

Suggested payload mapping:
- `otpRequest()` should send `applicationId`, `password`, `subscriberId`, optionally `applicationHash`, `applicationMetaData`.
- `otpVerify()` should send `applicationId`, `password`, `referenceNo`, `otp`.

## New Controller
- `app/Http/Controllers/API/OtpController.php`

Suggested methods:
- `requestOtp(Request $request)`
- `verifyOtp(Request $request)`

### requestOtp() input validation
- `app_id` required, string
- `subscriber_id` required, string
- `application_hash` nullable, string
- `application_meta_data` nullable, array

Flow:
1. Find app credentials from `install_apps` by `app_id`.
2. Build BDApps OTP request using stored app password.
3. Forward response to client as JSON.

### verifyOtp() input validation
- `app_id` required, string
- `reference_no` required, string
- `otp` required, string

Flow:
1. Find app credentials from `install_apps` by `app_id`.
2. Call BDApps OTP verify.
3. Return BDApps response to client.
4. If `statusCode === S1000` and `subscriptionStatus === REGISTERED`, upsert local subscription in `subscription_data` using:
   - `app_id` (from app config)
   - `subscribe_id` (from BDApps `subscriberId`)

Optional:
- If verify returns an unregistered status, optionally remove from `subscription_data`.

## Routes (Project API Endpoints)
Add these routes in `routes/web.php` under current BDApps API section:
- `POST /api/bdapps/otp/request`
- `POST /api/bdapps/otp/verify`

Example mapping:
- `/api/bdapps/otp/request` -> `API\OtpController@requestOtp`
- `/api/bdapps/otp/verify` -> `API\OtpController@verifyOtp`

## Expected Request/Response Contract (Your App)

### 1) Request OTP (project endpoint)
Request:
```json
{
  "app_id": "APP_000375",
  "subscriber_id": "tel:8801812345678",
  "application_hash": "abcdefgh",
  "application_meta_data": {
    "client": "MOBILEAPP",
    "device": "Samsung S10",
    "os": "android 8",
    "appCode": "https://play.google.com/store/apps/details?id=lk"
  }
}
```

Response:
- Forwarded BDApps response with `statusCode`, `statusDetail`, and `referenceNo` when successful.

### 2) Verify OTP (project endpoint)
Request:
```json
{
  "app_id": "APP_000375",
  "reference_no": "213561321321613",
  "otp": "123564"
}
```

Response:
- Forwarded BDApps response with `statusCode`, `statusDetail`, `subscriptionStatus`, `subscriberId`.

## cURL Examples

Request OTP:
```bash
curl -X POST "http://localhost/api/bdapps/otp/request" \
  -H "Content-Type: application/json" \
  -d '{
    "app_id":"APP_000375",
    "subscriber_id":"tel:8801812345678",
    "application_hash":"abcdefgh",
    "application_meta_data":{
      "client":"MOBILEAPP",
      "device":"Samsung S10",
      "os":"android 8",
      "appCode":"https://play.google.com/store/apps/details?id=lk"
    }
  }'
```

Verify OTP:
```bash
curl -X POST "http://localhost/api/bdapps/otp/verify" \
  -H "Content-Type: application/json" \
  -d '{
    "app_id":"APP_000375",
    "reference_no":"213561321321613",
    "otp":"123564"
  }'
```

## Error Handling
- If `app_id` does not exist in `install_apps`: return HTTP `404` with clear message.
- If validation fails: return HTTP `422`.
- If BDApps returns non-`S1000`: pass `statusCode` and `statusDetail` through to caller.
- If BDApps is unreachable: return HTTP `502` with gateway error message.

## Notes For Implementation
- Keep OTP logic in `BDAppsApi` for consistency with existing subscription calls.
- Do not trust client-provided app password; always read from `install_apps`.
- Preserve `tel:` format in subscriber IDs.
- Keep response format stable and JSON-only for easy client integration.
