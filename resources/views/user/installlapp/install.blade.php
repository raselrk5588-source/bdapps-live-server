@extends('layout.default')
@section('title_area')
Install App
@endsection
@section('main_section')
    <style>
        .premium-form-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.02);
        }
        .premium-form-title {
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
        .premium-form-title i {
            background: linear-gradient(135deg, #3A1C71 0%, #D76D77 50%, #FFAF7B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 24px;
            margin-right: 10px;
        }
        .premium-form-card .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }
        .premium-form-card .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 12px 15px;
            box-shadow: none;
            transition: all 0.3s;
            height: auto;
            background: #f8fafc;
        }
        .premium-form-card .form-control:focus {
            border-color: #D76D77;
            box-shadow: 0 0 0 3px rgba(215, 109, 119, 0.1);
            background: #fff;
        }
        .req { color: #d63384; margin-left: 3px; font-weight: bold; }
        .btn-submit {
            background: linear-gradient(135deg, #3A1C71 0%, #D76D77 50%, #FFAF7B 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(215, 109, 119, 0.3);
            width: 100%;
            margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(215, 109, 119, 0.4);
            color: white;
        }
        /* Fix spinner buttons height to match input */
        .spinner-buttons .btn {
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .spinner-down {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            background: #ff6b6b !important;
            border-color: #ff6b6b !important;
        }
        .spinner-up {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            background: #20c997 !important;
            border-color: #20c997 !important;
        }
    </style>

    <div class="content">
        <div class="container">
             @if(Session::has('message'))
                <div class="alert alert-{{Session::get("class")}}">{{Session::get("message")}}</div>
             @endif
            <!-- Start Form -->
                <div class="row">
                    @isset($add)
                    <div class="col-sm-8 col-md-7 col-lg-6">
                        <div class="premium-form-card">
                            <div class="premium-form-title">
                                <i class="md md-phonelink-setup"></i> Install New App
                            </div>
                            {!! Form::open(['url' => 'user/install']) !!}
                            @method("POST")
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="app_name">App Name</label><small class="req">*</small>
                                            <input  name="app_name" type="text" class="form-control"  required id="app_name" placeholder="Enter App Name">
                                            @error('app_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="app_id">App ID</label><small class="req">*</small>
                                            <input  name="app_id" type="text" class="form-control"  required id="app_id" placeholder="APP_XXXXXX">
                                            @error('app_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="password">App Password</label><small class="req">*</small>
                                            <input  name="password" type="text" class="form-control"  required id="password" placeholder="Enter Password">
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="sms_keyword">SMS Keyword</label><small class="req">*</small>
                                            <input  name="sms_keyword" type="text" class="form-control"  required id="sms_keyword" placeholder="Enter SMS Keyword">
                                            @error('sms_keyword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="ussd_code">USSD Code</label><small class="req">*</small>
                                            <input  name="ussd_code" type="text" class="form-control"  required id="ussd_code" placeholder="*213*XXXX#">
                                            @error('ussd_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="sms_time">SMS Time</label><small class="req">*</small>
                                            <div id="spinner4">
                                                <div class="input-group">
                                                    <div class="spinner-buttons input-group-btn">
                                                        <button type="button" class="btn spinner-down btn-danger waves-effect waves-light">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="sms_time" class="spinner-input form-control text-center" maxlength="2" readonly>
                                                    <div class="spinner-buttons input-group-btn">
                                                         <button type="button" class="btn spinner-up btn-success waves-effect waves-light">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('sms_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-top: 30px;">
                                            <select name="sms_time_format" id="sms_time_format" class="selectpicker form-control" data-container="body" >
                                                <option value="0">AM</option>
                                                <option value="1">PM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="category_id">Category</label><small class="req">*</small>
                                            <select name="category_id" id="category_id" class="selectpicker form-control" data-container="body" data-live-search=true>
                                                <option value="">--Select Category--</option>
                                                @isset($category)
                                                    @foreach($category as $key=>$value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <div class="form-group">
                                            <button type="submit" class="btn-submit" name="submit">
                                                <i class="md md-system-update"></i> Install App
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endisset
                </div>
        </div> <!-- container -->
    </div>
        <script type="text/javascript" src="{{asset("admin")}}/vendors/spinner/spinner.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#spinner4').spinner({value:8, step: 1, min: 1, max: 12});
        });
    </script>
@endsection
