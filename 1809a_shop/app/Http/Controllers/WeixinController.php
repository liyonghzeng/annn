<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

use \App\Wx;

use DB;

class WeixinController extends Controller
{
    public function nake()
    {
         echo $_GET['echostr'];

    }

    public function index(){
        $content=file_get_contents("php://input");
        $time=date('Y-m-d H:i:s',time());
        $str=$time.$content."\n";
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);    
        $data=simplexml_load_string($content);
//        echo 'ToUserName'.$data->ToUserName;
    //   echo 'ToUserName: '. $data->ToUserName;echo '</br>';        // 公众号ID
    //   echo 'FromUserName: '. $data->FromUserName;echo '</br>';    // 用户OpenID
    //   echo 'CreateTime: '. $data->CreateTime;echo '</br>';        // 时间戳
    //   echo 'MsgType: '. $data->MsgType;echo '</br>';              // 消息类型
    //   echo 'Event: '. $data->Event;echo '</br>';                  // 事件类型
    //   echo 'EventKey: '. $data->EventKey;echo '</br>';
        $wx_id = $data->ToUserName;             // 公众号ID
        $openid = $data->FromUserName;          //用户OpenID
        $event = $data->Event;          //事件类型
        if($event=='subscribe'){    //扫码事件
            $local_user = Wx::where(['openid'=>$openid])->first();
            if($local_user){        //用户之前关注过
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎回来 '. $local_user['nickname'] .']]></Content></xml>';
            }else{          //用户首次关注
                //获取用户信息
                $u = $this->getUserInfo($openid);
                //用户信息入库
                $u_info = [
                    'openid'    => $u['openid'],
                    'nickname'  => $u['nickname'],
                    'sex'  => $u['sex'],
                    'headimgurl'  => $u['headimgurl'],
                ];
                $id = WxUserModel::insertGetId($u_info);
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎关注 '. $u['nickname'] .']]></Content></xml>';
            }
        }
    
       
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