@extends('layout.default')
@section('title_area')
    Instruction
@endsection
@section('main_section')
<style>
    .instruction-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid rgba(0,0,0,0.02);
    }
    .instruction-title {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 15px;
        display: flex;
        align-items: center;
    }
    .instruction-title i {
        background: linear-gradient(135deg, #3A1C71 0%, #D76D77 50%, #FFAF7B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 24px;
        margin-right: 10px;
    }
    .instruction-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .instruction-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .instruction-label {
        font-weight: 600;
        color: #555;
        flex: 1.2;
        font-size: 15px;
    }
    .instruction-value {
        flex: 2;
        background: #f8fafc;
        padding: 12px 18px;
        border-radius: 10px;
        color: #d63384;
        font-family: monospace;
        font-size: 14px;
        word-break: break-all;
        border: 1px solid #e2e8f0;
        margin: 0 20px;
    }
    .instruction-action {
        flex: 0 0 110px;
        text-align: right;
    }
    .btn-copy {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(56, 239, 125, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-copy:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(56, 239, 125, 0.4);
        color: white;
    }
    .btn-copy i {
        margin-right: 6px;
    }
    
    @media (max-width: 768px) {
        .instruction-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .instruction-value {
            margin: 15px 0;
            width: 100%;
        }
        .instruction-action {
            width: 100%;
            text-align: left;
        }
    }
</style>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-md-12">
                    <div class="instruction-card">
                        <div class="instruction-title">
                            <i class="md md-settings-input-component"></i> API Integration Instructions
                        </div>
                        
                        <div class="instruction-item">
                            <div class="instruction-label">Allowed Host Address</div>
                            <div class="instruction-value" id="host_address">{{ $host_address }}</div>
                            <div class="instruction-action">
                                <button onclick="copyText('{{ $host_address }}')" class="btn-copy"><i class="fa fa-copy"></i> Copy</button>
                            </div>
                        </div>
                        
                        <div class="instruction-item">
                            <div class="instruction-label">Message Receiving URL</div>
                            <div class="instruction-value" id="sms_url">{{ $sms_url }}</div>
                            <div class="instruction-action">
                                <button onclick="copyText('{{ $sms_url }}')" class="btn-copy"><i class="fa fa-copy"></i> Copy</button>
                            </div>
                        </div>
                        
                        <div class="instruction-item">
                            <div class="instruction-label">USSD Receiving URL</div>
                            <div class="instruction-value">{{ $ussd_url }}</div>
                            <div class="instruction-action">
                                <button onclick="copyText('{{ $ussd_url }}')" class="btn-copy"><i class="fa fa-copy"></i> Copy</button>
                            </div>
                        </div>
                        
                        <div class="instruction-item">
                            <div class="instruction-label">Subscription Notification URL</div>
                            <div class="instruction-value">{{ $subscription_notify_url }}</div>
                            <div class="instruction-action">
                                <button onclick="copyText('{{ $subscription_notify_url }}')" class="btn-copy"><i class="fa fa-copy"></i> Copy</button>
                            </div>
                        </div>
                    </div>
                </div> <!-- col -->
            </div>
        </div> <!-- container -->
    </div>
    
    <script src="{{ asset('admin') }}/vendors/notifications/notify.min.js"></script>
    <script src="{{ asset('admin') }}/vendors/notifications/notify-metro.js"></script>
    <script src="{{ asset('admin') }}/vendors/notifications/notifications.js"></script>
    <script>
        function copyText(str) {
            const el = document.createElement('textarea');
            el.value = str;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            $.Notification.autoHideNotify('success', 'top right', "Copied to clipboard!");
            document.body.removeChild(el);
        }
    </script>
@endsection
