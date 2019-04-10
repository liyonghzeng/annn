<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

use DB;

class WeixinController extends Controller
{
    public function nake()
    {
         echo $_GET['echostr'];

    }

    public function index(){
        $content=file_get_contents("php://input");
        $data=simplexml_load_string($content);
//        echo 'ToUserName'.$data->ToUserName;
        echo  $data->ToUserName;
        echo  $CreateTime = $data->CreateTime;
        echo  $Event = $data->Event;
        echo  $data->EventKey;

        $time=date('Y-m-d H:i:s',time());
        $str=$time.$content."\n";
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);
        echo 'SUCCESS';
    }
    /**获取微信 AccessToren */
    public function AccessToren(){
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
        // echo "$url";die;

        $response=file_get_contents($url);
        // dd($arr=json_decode($response,true););
        // echo $response;die;
        $key='wx_access_token';
        Cache::get($key);
//         dd(Cache::get($key));
//         Cache::forget($key);
        if(!Cache::has($key)){
            echo "没";
            $arr=json_decode($response,true);
            // dd($arr['access_token']);
            Cache::put($key,$arr['access_token'],3600);
            // print_R($arr);
            return $arr['access_token'];
        }else{
            echo "有";
            $arr= Cache::get($key);
            return $arr;
        }
    }
    public  function test(){
        $access_token=$this->AccessToren();
        echo $access_token;
    }





}