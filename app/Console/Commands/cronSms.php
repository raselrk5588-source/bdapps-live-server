<?php

namespace App\Console\Commands;

use App\Http\Components\SmsSender;
use App\InstallApp;
use App\Content;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class cronSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendSms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Sms Daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Storage::put("time_cor.txt","work");
        $users=User::where("status",1)->get();
        $url = "https://developer.bdapps.com/sms/send";
        if($users)
        {
            $response='';
            foreach ($users as $key=>$value)
            {
                $install_apps=InstallApp::where("user_id",$value->id)->get();
                if($install_apps)
                {
                    foreach ($install_apps as $a_key=>$a_value)
                    {
                        $where_condition=['app_id'=>$a_value->id,'is_sent'=>0];
                        $sms_content=Content::where($where_condition)->orderBy("id","asc")->first();
                        $app_id = $a_value->app_id;
                        $message = isset($sms_content->content) ? $sms_content->content : "N/A" ;
                        $password = $a_value->password;
                        $sms_ob = new SmsSender($url, $app_id, $password);

                        if(!empty($sms_content)){
                            $response =   $sms_ob->broadcast($message);
                            //dd($response);
                            $res_obj = json_decode($response);

                            if($res_obj->statusCode == 'S1000'){
                                $sms_content->is_sent = true;
                                if($sms_content->save()){

                                    $data['message'] = "SMS sent to all subscriber ! and db updated successfully ";
                                    $data['response'] = $response;
                                    return $data;
                                }else{
                                    $data['message']= "SMS sent to all subscriber ! but Database update error !! ";
                                    $data['response'] = $response;
                                    return $data;
                                 }
                            }else{
                                $data['message']= "SMS not sent check server response statusCode & statusDetails for more" ;
                                $data['response'] = $response;
                                return $data;
                            }

                        }else{
                            $response['message']= "Database is empty or no more unsent message available ! please insert content";

                        }
                    }
                }
            }

           return $response;
        }
    }
}
