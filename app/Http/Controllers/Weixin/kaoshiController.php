<?php

namespace App\Http\Controllers\Weixin;

use App\Model\WeixinUser;
use App\Model\WechatCode;
use App\Model\OrderModel;
use App\Model\WechatUserOuick;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class kaoshiController extends Controller
{
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token

    /**
     * 获取access_token
     */
    public function access_token(){
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxcaeeec85ae352cb3&secret=2bbdb2d2c1b65d97df6f719669a170cc";
            $obj=json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $obj['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;
    }

    /**
     * 接入
     */
    public function wxEvent(){
        $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
        $FromUserName=$xml->FromUserName;
        $ToUserName=$xml->ToUserName;
        if($xml->MsgType=='event'){
            if($xml->Event=='subscribe'){
                $userinfo=WeixinUser::where(['FromUserName'=>$FromUserName])->first();
                $user=$this->userInfo($FromUserName);
                if($userinfo){
                    //已关注
                    $username=$user['nickname'];
                    $xmldata="<xml>
                            <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                            <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                            <CreateTime>time()</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[欢迎回来,$username]]></Content>
                            </xml>";
                    echo $xmldata;
                }else{
                    //首次关注
                    $data=[
                        'FromUserName'=>$user['openid'],
                        'CreateTime'=>time(),
                        'nickname'=>$user['nickname'],
                        'sex'=>$user['sex'],
                        'headimgurl'=>$user['headimgurl'],
                        'subscribe_time'=>$user['subscribe_time']
                    ];
                    $userobj=WeixinUser::insertGetId($data);
                    if($userobj){
                            $xmldata="<xml>
                            <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                            <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                            <CreateTime>time()</CreateTime>
                            <MsgType><![CDATA[text]]></MsgType>
                            <Content><![CDATA[你好,欢迎关注XXX公众号]]></Content>
                            </xml>";
                        echo $xmldata;
                    }
                }
            }
        }else if($xml->MsgType=='text'){
            if(strpos($xml->Content,"天气")!==false){
                    $xmldata="<xml>
                                <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                                <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                                <CreateTime>time()</CreateTime>
                                <MsgType><![CDATA[news]]></MsgType>
                                <ArticleCount>1</ArticleCount>
                                <Articles>
                                <item>
                                <Title><![CDATA[$xml->Content]]></Title>
                                <Description><![CDATA[天气详情]]></Description>
                                <PicUrl><![CDATA[https://tianqi.moji.com/liveview/picture/82423518]]></PicUrl>
                                <Url><![CDATA[http://laravel.myloser.club/kaoshi/tianqi]]></Url>
                                </item>
                                </Articles>
                                </xml>";
                echo $xmldata;
            }
        }
    }

    /**
     * 获取用户信息
     */
    public function userInfo($FromUserName){
//        $FromUserName="oRqEl1rmcayXz9Z7UzkapLaxv7AM";
        $access_token=$this->access_token();
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$FromUserName&lang=zh_CN";
        $obj=json_decode(file_get_contents($url),true);
        return $obj;
    }

    /**
     * 天气接口
     */
    public function tianqi(){
//        $url="http://wthrcdn.etouch.cn/WeatherApi?city=北京";
        $url="http://www.weather.com.cn/data/sk/101010100.html";
//        $obj=file_get_contents($url);
//        //解析XML
//        $xml = simplexml_load_file($obj.".xml"); //读取 XML数据
//        $newxml = $xml->asXML(); //标准化 XML数据
//        //记录天气日志
//        $log_str = date('Y-m-d H:i:s') . "\n" . $obj . "\n<<<<<<<";
//        file_put_contents('logs/wx_tianqi.log',$log_str,FILE_APPEND);
        $obj=json_decode(file_get_contents($url),true);
//        print_r($obj['weatherinfo']);die;
        return view('weixin.tianqi')->with('data',$obj['weatherinfo']);
    }
}