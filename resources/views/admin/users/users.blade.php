@extends('admin.layout.default')
@section('title_area')
    All Users
@endsection
@section('main_section')

    <div class="content">
        <div class="container">
            <!-- Start Widget -->
                <div class="row">
                    <div class="col-md-12">
                          <div class="panel panel-border panel-info">
                               <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne" class="collapsed">
                                            Users List
                                        </a>
                                    </h3>
                                </div>
                              <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3 m-b-10 pull-left">
                                           <div class="form-group">
                                             <label for="filter_by">Filter By</label>
                                                <select id="filter_by"  name="filter_by"  class="form-control selectpicker" >
                                                    <option value="">All</option>
                                                    <option value="0">New</option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 m-b-10 pull-left">
                                           <div class="form-group">
                                            <label for="approved_by">Approved By</label>
                                                <select id="approved_by"  name="approved_by"  class="form-control selectpicker" >
                                                    <option value="">All</option>
                                                    @foreach($users as $value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 m-b-10 pull-left">
                                            <div class="form-group">
                                            <label for="university_id">University</label>
                                                <select id="university_id"  name="university_id"  class="form-control selectpicker" >
                                                    <option value="">All</option>
                                                    @foreach($university as $value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 m-b-10 pull-right  m-t-22">
                                             <div class="input-group">
                                                <input type="text" name="search_key" placeholder="Search Email Or Phone" id="search_key" class="form-control">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info" id="add_button" type="button">
                                                        <i class="md md-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                  <div class="row">
                                        <div class="col-md-12">
                                            <div  style="overflow: hidden">
                                                <div class="table-responsive">
                                                    <div id="user_loading">
                                                        <div class="cv-spinner">
                                                            <span class="spinner"></span>
                                                        </div>
                                                    </div>
                                                    <table id="users" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl</th>
                                                            <th>Name</th>
                                                            <th>Phone</th>
                                                            <th>Email</th>
                                                            <th>University</th>
                                                            <th>Approved By</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                  </div>
                              </div>
                          </div>
                    </div>
                </div>
        </div> <!-- container -->
    </div>
    <script !src="">
        $(document).ready(function () {
            $("#search_key").on("change",function () {
			    get_view(false);
                return false;
            });
            $("#filter_by,#approved_by,#university_id").on("change",function () {
                get_view(false);
                return false;
            });
            $("#users").on("click",'.pagination li a',function () {
                var page_url=$(this).attr("href");
                if(page_url=="javascript:void(0)")
                {
                    return false;
                }
                get_view(page_url);
                return false;
            });
            get_view(false);
         function get_view(page_url)
        {
			var university_id=$("#university_id").val();
			var filter_by=$("#filter_by").val();
			var approved_by=$("#approved_by").val();
        	var search_key=$("#search_key").val();
        	var base_url="{{url('admin/user-view')}}";
        	if(page_url)
			{
				base_url=page_url;
			}
            $.ajax({
                url:base_url,
                type:"get",
                dataType:"json",
				data:{
                	"search_key":search_key,
					"filter_by":filter_by,
					"university_id":university_id,
					"approved_by":approved_by
				},
                beforeSend: function(){
                		$("#user_loading").fadeIn(300);　
                },
                success:function(data){
                   $("#users tbody").html(data.html);
                	$("#user_loading").fadeOut(300);　
                },
                error:function (e) {
                	$("#user_loading").fadeOut(300);
				}
            });
        }
        });
    </script>
@endsection
