<?php

use Symfony\Component\HttpFoundation\Request;

if (!function_exists('textshorten')) {

    function textshorten($text,$limit=400){
           $text=$text." ";
           $text=substr($text,0,$limit);
           $text=substr($text,0,strrpos($text, ' '));
           return $text=$text." .....";
       }
}
if (!function_exists('setMessage')) {

    function setMessage($key,$class,$message) {
       session()->flash($key, $message);
       session()->flash("class", $class);
        // session()->flash($key,'<div class="alert alert-'.$class.'">'.$message.'</div>');
        return true;
    }

}
if (!function_exists('active_link')) {

    function set_Topmenu($top_menu_name) {
        $session_top_menu = session('top_menu');
        if ($session_top_menu == $top_menu_name) {
            return 'active';
        }
        return "";
    }

    function set_Submenu($sub_menu_name) {
        $session_sub_menu = session('sub_menu');
        if ($session_sub_menu == $sub_menu_name) {
            return 'active';
        }
        return "";
    }

}
if (!function_exists('debug_r')) {

    function debug_r($value) {
        echo "<pre>";
            print_r($value);
        echo "</pre>";
        die();
    }
}
if (!function_exists('debug_v')) {

    function debug_v($value) {
        echo "<pre>";
            var_dump($value);
        echo "</pre>";
        die();
    }
}

if (!function_exists('logFile')) {

   function logFile($rtn){
        $f=fopen(asset("public/log.txt"),"a");
        fwrite($f, $rtn . "\n");
        fclose($f);
	}

}
