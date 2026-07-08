<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$app_name}}</title>
    <style type="text/css">
         #watermark {
            position: fixed;
            font-size: 15px;
            top: -20;
            width: 100%;
            text-align: center;
            opacity: .5;
            word-spacing: 150px;
            text-transform: uppercase;
            /*transform: rotate(-10deg);*/
            transform-origin: 50% 50%;
            z-index: -1000;
          }
    </style>
</head>
<body>
    <div id="watermark">
         Unexsoft Unexsoft Unexsoft
      </div>
    <h1>{{$app_name}}</h1>
    <p><strong>App Name:</strong> {{$app_name}}</p>
    <p><strong>App ID:</strong> {{$app_id}}</p>
    <p><strong>App Description:</strong><br /> {{$long_desc}}</p>
    <h4>How to subscribe:</h4>
    <p>* SMS: User will have to type “START {{$sms_keyword}} ” and send to 21213 to complete the subscription.</p>
    <p>* USSD: Dial {{$ussd_code}} then press 1 (TBD) to subscribe.</p>
    <p>* Other mode: N/A</p>
    <h4>How to Unsubscribe:</h4>
    <p>*SMS: User will have to type “STOP {{$sms_keyword}} ” and send to 21213 to complete the unsubscription</p>
    <p>* USSD: Dial {{$ussd_code}} then press 1 (TBD) to subscribe.</p>
    <p>* Other mode: N/A</p>
    <h4>Charge:</h4>
    <p>TK “2”+ (VAT + SD + SC)/day with Auto Renewal.</p>
    <p>* {{$short_desc}}</p>
    <p>*Subscription charge will cost “2”+ (VAT + SD + SC)/ day with Auto Renewal</p>
    <h4>Offer Details</h4>
    <p>1SMS.</p>

    <p>
    <strong>Support Contact</strong><br>
        {{Auth::user()->name}}<br>
        {{Auth::user()->email}}
    </p>








</body>
</html>
