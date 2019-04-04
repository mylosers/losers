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
    public function wxEvent(Request $request)
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
        $EventKey = $xml->EventKey;
        $Ticket = $xml->Ticket;
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
                }else if($event=='SCAN'){
                    $u = WechatCode::where(['key' => $EventKey,'openid'=>$FromUserName])->first();
                    $code_data=[
                        'openid' => $FromUserName,
                        'c_time' => $CreateTime,
                        'key'=>$EventKey,
                        'ticket'=>$Ticket
                    ];
                    if($u){
                        //更新时间和ticket
                        WechatCode::where(['key'=>$EventKey,'openid'=>$FromUserName])->update(['c_time'=>$CreateTime,'ticket'=>$Ticket]);
                    }else{
                        WechatCode::insertGetId($code_data);
                    }
                    $redis=new \Redis();
                    $redis->connect('127.0.0.1',6379);
                    $code_id=$redis->incr('code_id');
                    $key="code_".$code_id;
                    $res=$redis->zAdd('code2',"id","$code_data[openid]"."$code_data[key]");
                    if($res==1) {
                        $redis->hset("$code_data[openid]"."$code_data[key]",'id',"$code_id");
                        $redis->hset("$code_data[openid]"."$code_data[key]",'openid',"$code_data[openid]");
                        $redis->hset("$code_data[openid]"."$code_data[key]",'ticket',"$code_data[ticket]");
                        $redis->hset("$code_data[openid]"."$code_data[key]",'key',"$code_data[key]");
                        $redis->hset("$code_data[openid]"."$code_data[key]",'c_time',"$code_data[c_time]");
                    }
                    $data_info=WechatUserOuick::where(['openid'=>$FromUserName])->first();
                    if($data_info){
                        $token = substr(md5(time().mt_rand(1,99999)),10,10);
                        setcookie('openid',$FromUserName,time()+86400,'/','',false,true);
                        setcookie('token',$token,time()+86400,'/logins','',false,true);

                        $request->session()->put('u_token',$token);
                        $request->session()->put('openid',$FromUserName);
                        $xml_response = '<xml><ToUserName><![CDATA['.$FromUserName.']]></ToUserName><FromUserName><![CDATA['.$ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. 'Hello World, 欢迎登陆'. date('Y-m-d H:i:s') .']]></Content></xml>';
                        echo $xml_response;
                    }else{

                    }
                }
            }else if($MsgType=="text"){
                $data=[
                    'text'=>$Content,
                    'c_time'=>$CreateTime,
                    'openid'=>$FromUserName,
                    'status'=>2
                ];
                DB::table('kefu')->insert($data);
            }
        }
    }

    /**
     * 扫码登陆
     */
    /*private function logins(Request $request){
        $token = substr(md5(time().mt_rand(1,99999)),10,10);
        setcookie('openid',$data_info['openid'],time()+86400,'/','',false,true);
        setcookie('token',$token,time()+86400,'/logins','',false,true);

        $request->session()->put('u_token',$token);
        $request->session()->put('openid',$data_info['openid']);
    }*/

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

    /**
     * 生成二维码ticket
     */
    public function code(){
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url_str="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
        $data=[
            "expire_seconds"=>604800,
            "action_name"=>"QR_SCENE",
            "action_info"=>[
                "scene"=>[
                    "scene_id"=>789
                ]
            ]
        ];
        $client = new GuzzleHttp\Client(['base_uri' => $url_str]);
        $r = $client->request('POST', $url_str, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $respone_arr = json_decode($r->getBody(), true);
        $ticket=$respone_arr['ticket'];
        $this->imgcode($ticket);
    }

    /**
     * 二维码生成
     */
    private function imgcode($ticket){
        $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        $data = file_get_contents($url);
        $arr=rand(1000,9999);
        file_put_contents("./upload/789.jpg",$data);
        echo "<img src='$url'>";
    }

    /**
     * 支付二维码生成
     */
    public function PayCode(){
        $oid=$_GET['oid'];
        //验证订单状态 是否支付 是否有效
        $order_info=OrderModel::where(['oid'=>$oid])->first()->toArray();
        if(!$order_info){
            header('Refresh:2;url=/order');
            echo ("订单 ".$oid. "不存在！");die;
        }
        //检查订单状态 是否已支付 已过期 已删除
        if($order_info['pay_time'] > 0){
            header('Refresh:2;url=/order');
            echo ("此订单已被支付，无法再次支付");die;
        }
        //检查订单状态 是否已被删除
        if($order_info['is_delete']==1){
            header('Refresh:2;url=/order');
            echo ('此订单已被删除，无法支付');die;
        }
        $url="https://api.mch.weixin.qq.com/pay/unifiedorder";
        $total=$_GET['total'];
        $str=md5(time());
        $key="7c4a8d09ca3762af61e59520943AB26Q";
        $orderId=$_GET['orderId'];
        $ip=$_SERVER['REMOTE_ADDR'];
        $notify_url="http://laravel.myloser.club/wechat/PayCodeAdd?oid=$oid";
        $arr=[
            'appid'=>"wxd5af665b240b75d4",
            'mch_id'=>"1500086022",
            'nonce_str'=>$str,
            'sign_type'=>'MD5',
            'body'=>$orderId,
            'out_trade_no'=>$orderId,
            'total_fee'=>$total,
            'spbill_create_ip'=>$ip,
            'notify_url'=>$notify_url,
            'trade_type'=>"NATIVE",

        ];
        ksort($arr);
        $strParams=urldecode(http_build_query($arr));
        $strParams.="&key=$key";
        $endStr=md5($strParams);
        $arr['sign']=$endStr;
        $obj=new \url();
        $arr=$obj->arr2Xml($arr);
        $info=$obj->sendPost($url,$arr);
        $objxml=simplexml_load_string($info);
        $url=$objxml->code_url;
        return view('weixin.paycode',['codeurl'=>$url]);
    }

    /**
     * 微信扫码支付回调
     */
    public function PayCodeAdd(){
        $data = file_get_contents("php://input");
        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象
        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_code.log',$log_str,FILE_APPEND);
        $ToUserName ="gh_dad3107d10b3";         //开发者微信号
        $FromUserName ="oRqEl1rmcayXz9Z7UzkapLaxv7AM";     //发送方帐号  用户openid
        $oid=$xml->oid;
        $wechat=[
            'pay_time'=>time(),
            'uid'=>25,
            'pay_amount'=>rand(1111,9999),
            'is_pay'=>1
        ];
        $info=OrderModel::where(['oid'=>$oid])->update($wechat);
        if($info){
            $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
            $data=[
                "touser"=>'oRqEl1rmcayXz9Z7UzkapLaxv7AM',
                "template_id"=>"Av_-FByzbsbpzWUzStK6V0IOR2RFS0wNzz5IJrZghpM",
                'data'=>[
                    'name'=>[
                        'value'=>"支付成功",
                        "color"=>"#173177"
                    ]
                ]
            ];
            $obj=new \url();
            $data=json_encode($data,JSON_UNESCAPED_UNICODE);
            $obj->sendPost($url,$data);
        }

    }
}