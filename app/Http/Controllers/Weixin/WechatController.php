<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

class WechatController extends Controller
{

    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    /**
     * 首次接入
     */
    public function wechat()
    {
        //$get = json_encode($_GET);
        //$str = '>>>>>' . date('Y-m-d H:i:s') .' '. $get . "<<<<<\n";
        //file_put_contents('logs/weixin.log',$str,FILE_APPEND);
        echo $_GET['echostr'];
    }

    /**
     * 接收微信服务器事件推送
     */
    public function wxEvent()
    {
        $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
        $ToUserName = $xml->ToUserName;         //开发者微信号
        $FromUserName = $xml->FromUserName;     //发送方帐号  用户openid
        $CreateTime = $xml->CreateTime;         //消息创建时间
        $MsgType = $xml->MsgType;               //消息类型，
        $Content = $xml->Content;               //文本消息内容
        $MsgId = $xml->MsgId;                   //消息id
        $event = $xml->Event;
        if(isset($xml->MsgType)) {
            if($MsgType=='event'){           //判断事件类型
                if($event=='subscribe') {    //扫码关注事件
                    //获取用户信息
                    $user_info = $this->getUserInfo($FromUserName);
                    //保存用户信息
                    $u = WeixinUser::where(['FromUserName' => $FromUserName])->first();
                    if ($u) {       //用户不存在
                        //echo '用户已存在';
                    } else {
                        $user_data = [
                            'FromUserName' => $FromUserName,
                            'CreateTime' => time(),
                            'nickname' => $user_info['nickname'],
                            'sex' => $user_info['sex'],
                            'headimgurl' => $user_info['headimgurl'],
                            'subscribe_time' => $CreateTime,
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        //var_dump($id);
                    }
                    $xml_response = '<xml><ToUserName><![CDATA['.$FromUserName.']]></ToUserName><FromUserName><![CDATA['.$ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 欢迎关注'. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;
                }
            }
        }
    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($FromUserName)
    {
//        $openid = 'oRqEl1rmcayXz9Z7UzkapLaxv7AM';
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$FromUserName.'&lang=zh_CN';
        $data = json_decode(file_get_contents($url),true);
        echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }

    /**
     * 添加用户标签
     */
    public function getWXtag(){
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
        $data = json_decode(file_get_contents($url),true);
        dd($data);
    }

}