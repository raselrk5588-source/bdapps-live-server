@extends('layout.default')
@section('title_area')
Send SMS
@endsection
@section('main_section')
    <div class="content">
        <div class="container">
             @if(Session::has('message'))
                <div class="alert alert-{{Session::get("class")}}">{{Session::get("message")}}</div>
             @endif
            <!-- Start Widget -->
                <div class="row">
                    {!! Form::open(['url' => 'user/sendsms']) !!}
                    @method("POST")
                        <div class="col-sm-6">
                            <div class="panel panel-border panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne" class="collapsed">
                                           Send SMS
                                        </a>
                                    </h3>
                                </div>
                                    <div class="panel-body">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="sms_body">SMS Body</label><small class="req">*</small><br/>
                                                <textarea name="sms_body" maxlength="300" required class="form-control" placeholder="Type Your SMS" id="sms_body" cols="30" rows="5"></textarea>
                                                <code id="remaining">300 characters remaining</code>
                                                @error('sms_body')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="app_id">APP Name</label><small class="req">*</small><br/>
                                                <select name="app_id" id="app_id" class="form-control selectpicker " required data-container="body" data-live-search=true >
                                                    <option value="">--Select--</option>
                                                    @isset($app_name)
                                                        @foreach($app_name as $value)
                                                            <option value="{{$value->id}}">{{$value->app_name}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                                @error('app_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group pull-right m-t-22">
                                                <input type="submit" class=" btn btn-primary pull-right" value="Send SMS" name="submit" />
                                            </div>
                                        </div>
                                    </div> <!-- panel-body -->
                            </div> <!-- panel -->
                        </div> <!-- col -->
                        {!! Form::close() !!}
                </div>
        </div> <!-- container -->
    </div>
    <script >
        $(document).ready(function(){
            $('#sms_body').on("keyup",function(){
                var chars = this.value.length,
                    remaining = 300-chars;
               $('#remaining').text(remaining + ' characters remaining');
            });
        });
    </script>
@endsection
