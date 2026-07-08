  <!-- ========== Left Sidebar Start ========== -->
  <style>
      .sidebar-user-card {
          padding: 25px 20px;
          background: linear-gradient(135deg, #3A1C71 0%, #D76D77 50%, #FFAF7B 100%);
          margin: 15px;
          border-radius: 15px;
          text-align: center;
          box-shadow: 0 5px 15px rgba(0,0,0,0.2);
          position: relative;
      }
      .sidebar-user-card .avatar {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          background: rgba(255,255,255,0.2);
          backdrop-filter: blur(5px);
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 0 auto 10px auto;
          border: 2px solid rgba(255,255,255,0.5);
          color: white;
          font-size: 24px;
          font-weight: 700;
          text-transform: uppercase;
          box-shadow: 0 5px 10px rgba(0,0,0,0.1);
      }
      .sidebar-user-card .dropdown-toggle {
          color: white !important;
          font-size: 16px;
          font-weight: 600;
          letter-spacing: 0.5px;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
      }
      .sidebar-user-card p {
          color: rgba(255,255,255,0.9) !important;
          font-size: 12px;
          margin-top: 5px;
          text-transform: uppercase;
          letter-spacing: 1px;
          font-weight: 500;
      }
      .sidebar-user-card .dropdown-menu {
          border-radius: 10px;
          box-shadow: 0 8px 25px rgba(0,0,0,0.15);
          border: none;
          padding: 10px 0;
          margin-top: 10px;
          text-align: left;
      }
      .sidebar-user-card .dropdown-menu > li > a {
          padding: 10px 20px;
          color: #555;
          font-weight: 500;
          transition: all 0.2s ease;
      }
      .sidebar-user-card .dropdown-menu > li > a:hover {
          background-color: rgba(215, 109, 119, 0.05);
          color: #D76D77;
      }
      .sidebar-user-card .dropdown-menu i {
          margin-right: 10px;
          font-size: 16px;
          width: 20px;
          text-align: center;
          color: #888;
          transition: all 0.2s ease;
      }
      .sidebar-user-card .dropdown-menu > li > a:hover i {
          color: #D76D77;
      }
      #wrapper.enlarged .sidebar-user-card {
          display: none !important;
      }
  </style>
  <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">
            <div class="sidebar-user-card">
                <div class="avatar">
                    {{ substr(config('app.name'), 0, 1) }}
                </div>
                <div class="user-info">
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">{{ config('app.name')}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="md md-settings"></i> Reset Profile</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="md md-settings-power"></i> Logout</a></li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                        </ul>
                    </div>

                    <p class="text-muted m-0">
                        {{ optional(Auth::user()->role)->name ?? (Auth::user()->role_id == 1 ? 'Admin' : (Auth::user()->role_id == 2 ? 'User' : 'Unknown')) }}
                    </p>
                </div>
            </div>
            <!--- Divider -->
            <div id="sidebar-menu">
                <ul>
                    <li>
                        <a href="{{ route("admin.dashboard")}}" class="waves-effect {{set_Topmenu("dashboard")}}"><i class="md md-home"></i><span> Dashboard </span></a>
                    </li>
                    <li>
                        <a href="{{ route("admin.user")}}" class="waves-effect {{set_Topmenu("user")}}"><i class="fa fa-user"></i><span> Users </span></a>
                    </li>
                    <li>
                        <a href="{{ route("admin.category.index")}}" class="waves-effect {{set_Topmenu("category")}}"><i class="fa fa-list"></i><span> Categories </span></a>
                    </li>
                    <li>
                        <a href="{{ route("admin.university.index")}}" class="waves-effect {{set_Topmenu("university")}}"><i class="fa fa-building"></i><span> Universities </span></a>
                    </li>
                    <li>
                        <a href="{{ route("admin.installapp.index")}}" class="waves-effect {{set_Topmenu("installapp")}}"><i class="fa fa-mobile"></i><span> Installed Apps </span></a>
                    </li>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- Left Sidebar End -->
