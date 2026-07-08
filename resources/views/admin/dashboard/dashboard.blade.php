@extends('admin.layout.default')
@section('title_area')
    Dashboard
@endsection
@section('main_section')
<style>
    .premium-widget {
        border-radius: 20px;
        padding: 30px 25px;
        color: white;
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .premium-widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    .premium-widget .icon-box {
        background: rgba(255,255,255,0.2);
        border-radius: 15px;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        backdrop-filter: blur(5px);
    }
    .premium-widget .stats-info {
        text-align: right;
        z-index: 1;
    }
    .premium-widget .stats-info h3 {
        font-size: 36px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: #fff;
        line-height: 1;
    }
    .premium-widget .stats-info p {
        font-size: 15px;
        margin: 0;
        font-weight: 500;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    /* Gradients */
    .grad-blue { background: linear-gradient(135deg, #3A1C71 0%, #D76D77 50%, #FFAF7B 100%); }
    .grad-green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .grad-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    
    .bg-circle {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        top: -30px;
        right: -30px;
        z-index: 0;
    }
    .page-title-box {
        padding-bottom: 25px;
        border-bottom: 1px solid #eaeaea;
        margin-bottom: 30px;
    }
    .page-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: #333;
        font-size: 24px;
        margin: 0;
    }
</style>

    <div class="content">
     @if(Session::has('message'))
        <div class="alert alert-{{Session::get("class")}}">{{Session::get("message")}}</div>
     @endif
    <div class="container">
        <!-- Page-Title -->
        <div class="row page-title-box">
            <div class="col-sm-12">
                <h4 class="pull-left page-title">Welcome to your Dashboard</h4>
                <ol class="breadcrumb pull-right" style="background:transparent; margin-bottom:0; padding:0; margin-top:5px;">
                    <li><a href="#">{{config('app.name')}}</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- Start Widget -->
            <div class="row">
                <div class="col-md-4 col-sm-6 col-lg-4">
                    <div class="premium-widget grad-blue">
                        <div class="bg-circle"></div>
                        <div class="icon-box">
                            <i class="md md-perm-identity"></i>
                        </div>
                        <div class="stats-info">
                            <h3>{{$total_active_user ?? '0'}}</h3>
                            <p>Active Users</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-sm-6 col-lg-4">
                    <div class="premium-widget grad-purple">
                        <div class="bg-circle"></div>
                        <div class="icon-box">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="stats-info">
                            <h3>{{$total_pending_user ?? '0'}}</h3>
                            <p>Pending Users</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 col-sm-6 col-lg-4">
                    <div class="premium-widget grad-green">
                        <div class="bg-circle"></div>
                        <div class="icon-box">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <div class="stats-info">
                            <h3>{{$new_register_user ?? '0'}}</h3>
                            <p>New Registrations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div>
@endsection
