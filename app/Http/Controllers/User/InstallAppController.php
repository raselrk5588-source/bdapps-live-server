<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Http\Controllers\Controller;
use App\InstallApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
class InstallAppController extends Controller
{
     public function __construct()
    {

        $this->middleware(function ($request, $next) {
            Session::put('top_menu',"installApp");
            Session::put('sub_menu',"installApp");
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['add']=true;
        $this->data['category']=Category::all();
        $limit=InstallApp::where("user_id",Auth::user()->id)->count();
        if($limit>=config('app.total_install_limit'))
        {
            setMessage("message",'danger','Your App Limit Over');
            return redirect()->route("user.dashboard");
        }
        return view('user.installlapp.install',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//       dd($request->all());
       $data=$request->validate([
            'app_name' => ['required', 'string', 'unique:install_apps'],
            'app_id' => ['required', 'string', 'max:11', 'unique:install_apps'],
            'password' => ['required', 'string'],
            'sms_time' => ['required'],
            'sms_time_format' => ['required'],
            'category_id' => ['required'],
            'sms_keyword' => ['required'],
            'ussd_code' => ['required']
       ]);
       $data['user_id']=Auth::user()->id;
       InstallApp::create($data);
       setMessage('message','success','App Install Successfully');
       return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InstallApp  $installApp
     * @return \Illuminate\Http\Response
     */
    public function show(InstallApp $installApp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InstallApp  $installApp
     * @return \Illuminate\Http\Response
     */
    public function edit(InstallApp $installApp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InstallApp  $installApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InstallApp $installApp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InstallApp  $installApp
     * @return \Illuminate\Http\Response
     */
    public function destroy(InstallApp $installApp)
    {
        //
    }
}
