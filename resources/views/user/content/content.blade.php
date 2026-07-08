@extends('layout.default')
@section('title_area')
Content SMS
@endsection
@section('main_section')
    <div class="content">
        <div class="container">
             @if(Session::has('message'))
                <div class="alert alert-{{Session::get("class")}}">{{Session::get("message")}}</div>
             @endif
            <!-- Start Widget -->
                <div class="row">
                    <form id="content_add">
                     @csrf
                    @method("POST")
                        <div class="col-sm-8">
                            <div class="panel panel-border panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne" class="collapsed">
                                           Schedule SMS Content
                                        </a>
                                    </h3>
                                </div>
                                    <div class="panel-body">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="app_id">APP Name</label><small class="req">*</small><br/>
                                                <select name="app_id" id="app_id" class="form-control selectpicker "  required data-container="body" data-live-search=true >
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
                                            <div id="content_body">
                                                <div class="content_body_sms" id="1">
                                                    <div class="form-group">
                                                        <label for="sms_body">SMS Body</label><small class="req">*</small><br/>
                                                        <textarea name="sms_body[]" maxlength="300" required class="form-control" placeholder="Type Your SMS" id="sms_body" cols="30" rows="5"></textarea>
                                                     <button class="btn btn-success m-l-10 m-t-5 plus_button" type="button"><i class="fa fa-plus"></i> </button><code id="remaining_1" class="pull-right">300 characters remaining</code>
                                                    </div>
                                                </div>
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

<script src="{{asset("admin")}}/vendors/notifications/notify.min.js"></script>
<script src="{{asset("admin")}}/vendors/notifications/notify-metro.js"></script>
<script src="{{asset("admin")}}/vendors/notifications/notifications.js"></script>
    <script >
        var x=0;
        var y=2;
        $(document).ready(function(){
            $('#content_body').on("keyup",'#sms_body',function(){
                var chars = this.value.length,
                    remaining = 300-chars;
                var id=$(this).closest(".content_body_sms").attr("id");
               $("#remaining_"+id).text(remaining + ' characters remaining');
            });

            $("#content_add").on("submit",function (e) {
                e.preventDefault();
                $.ajax({
                   data:$(this).serialize(),
                   url:"{{route("user.content")}}",
                    type:"POST",
                    dataType:"json",
                    beforeSend:function(){
                       $("#overlay").fadeIn(300);　
                    },
                    success:function (data) {
                          $.Notification.autoHideNotify('success', 'top right',data.success);
                          $("#overlay").fadeOut(300);　
                          $("#content_add").trigger("reset");
                          $(".content_body_sms_2").remove();
                          $(".selectpicker").selectpicker("refresh");
                          y=2;
                          x=1;
                    },
                    error:function (e) {
                        $.Notification.autoHideNotify('error', 'top right',"Something Wrong");
                        $("#overlay").fadeOut(300);　
                    }

                });
            });
        });
    </script>

<script>
     $(document).ready(function(){
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.plus_button'); //Add button selector
        var wrapper = $('#content_body'); //Input field wrapper

        //Once add button is clicked
        $('#content_body').on('click','.plus_button',function(){
            //Check maximum number of input fields
            if(x < maxField){
                x++; //Increment field counter
                $(wrapper).append(append_data(y++)); //Add field html
            }
        });
		function append_data(y)
		{
			var row="";
			row+='<div class="content_body_sms" id="'+y+'">';
			row+='<div class="content_body_sms_2"">';
			row+='<div class="col-sm-12">';
					row+='<div class="form-group">';
						row+='<label for="sms_body">SMS Body</label><small class="req">*</small><br/>';
						row+='<textarea name="sms_body[]" maxlength="300" required class="form-control" placeholder="Type Your SMS" id="sms_body" cols="30" rows="5"></textarea>';
						row+='<button class="btn btn-danger m-t-5 minus_button" type="button"><i class="fa fa-minus"></i> </button> <button class="btn btn-success m-l-10 m-t-5 plus_button" type="button"><i class="fa fa-plus"></i> </button><code id="remaining_'+y+'" class="pull-right">300 characters remaining</code>';
					row+='</div>';
				row+='</div>';
				row+='</div>';
				row+='</div>';
				return row;
		}
        //Once remove button is clicked
        $(wrapper).on('click', '.minus_button', function(e){
            e.preventDefault();
           // $("#content_body .content_body_sms:last-child").remove();
            $(this).closest('.content_body_sms').remove();
            x--; //Decrement field counter
        });
     });
  </script>
@endsection
