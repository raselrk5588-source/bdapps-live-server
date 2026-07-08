<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\InstallApp;
use App\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
class ContentController extends Controller
{
   public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session::put('top_menu',"content");
            Session::put('sub_menu',"content");
            return $next($request);
        });
    }
    public function index()
    {
        $this->data['app_name']=InstallApp::where("user_id",Auth::user()->id)->get();
        return view("user.content.content",$this->data);
    }

    public function store(Request $request)
    {
        $app_id=$request->input("app_id");
        $sms_array=$request->input("sms_body");
        $data=array();
        $app_info=Content::where("app_id",$app_id)->orderBy("id","desc")->first();
        if($app_info)
        {
            $lastdate=$app_info->content_date;
        }
        else{
              $lastdate=date("Y-m-d",strtotime("-1 day"));
        }
        $i=1;
        foreach ($sms_array as $key=>$value)
        {
            $data[$key]['app_id']=$app_id;
            $data[$key]['content']=strip_tags($value);
            $data[$key]['content_date']=date("Y-m-d",strtotime("+$i day", strtotime($lastdate)));
            $i++;
        }
        Content::insert($data);
        return response()->json(['success'=>"Content Upload Successfully"]);
    }

    public function appContent($app_id,Request $request)
    {
        $where_condition=['app_id'=>$app_id,'is_sent'=>false];
        $this->data['content']=Content::with("installApp")->where($where_condition)->paginate(10);
        if(count($this->data['content'])<=0)
        {
            setMessage("message",'danger','No Content Found');
            return redirect()->route("user.dashboard");
        }
        $page=$request->input('page');
        if($page<=1)
        {
            $this->data['sl_counter']=1;
        }
        else
        {
            $this->data['sl_counter']=$page*10-9;
        }
        return view('user.content.app-content',$this->data);
    }

}
