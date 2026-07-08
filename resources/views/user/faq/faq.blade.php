@extends('layout.default')
@section('title_area')
FAQ Generator
@endsection
@section('main_section')
    <div class="content">
        <div class="container">
             @if(Session::has('message'))
                <div class="alert alert-{{Session::get("class")}}">{{Session::get("message")}}</div>
             @endif
            <!-- Start Widget -->
                <div class="row">
                    {!! Form::open(['url' => 'user/faq_generator']) !!}
                    @method("POST")
                        <div class="col-sm-6">
                            <div class="panel-group panel-group-joined" id="accordion-test">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne" class="collapsed">
                                               Faq Generator Information
                                            </a>
                                        </h3>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
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
                                                <div class="form-group">
                                                    <label for="sms_keyword">SMS Keyword</label><small class="req">*</small>
                                                    <input  name="sms_keyword" type="text" class="form-control"  requiredid="sms_keyword" placeholder="SMS Keyword">
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
                                                    @error('sms_keyword')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="long_desc">Long Description</label><small class="req">*</small><br/>
                                                    <code>&lt;Service Name&gt; is a &lt;Service type&gt; that offers &lt;function of the service&gt;. The service offers &lt;Details&gt; of the service. As details as possible&gt; </code>
                                                    <textarea name="long_desc"  class="form-control" placeholder="Long Description" required id="long_desc" cols="30" rows="5"></textarea>
                                                    @error('long_desc')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="short_desc">Short Description</label><small class="req">*</small><br/>
                                                    <code>This is a subscription based &lt;Please mention the type&gt; service.</code>
                                                    <textarea name="short_desc"  class="form-control" placeholder="Short Description" required id="short_desc" cols="30" rows="5"></textarea>
                                                    @error('long_desc')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group pull-left m-t-22">
                                                    <input type="submit" class=" btn btn-primary pull-right" value="Generate" name="submit" />
                                                </div>
                                            </div>
                                        </div> <!-- panel-body -->
                                    </div>
                                </div> <!-- panel -->
                            </div>
                        </div> <!-- col -->
                        {!! Form::close() !!}
                </div>
        </div> <!-- container -->
    </div>
@endsection
