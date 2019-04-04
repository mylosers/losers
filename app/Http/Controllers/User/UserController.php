<?php

namespace App\Http\Controllers\User;
use App\Model\RequestModel;
use App\Model\WechatUserOuick;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;

/**
 * Class UserController
 * @package App\Http\Controllers\User
 * 用户控制器
 */
class UserController extends Controller
{

/*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    public function index(){
        return view('user/user');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 展示注册视图
     */
    public function request(){
        $data = [
            'title'     => '注册',
        ];
        return view('user/request',$data);
    }
    /**
     * 注册
     */
    public function requestAdd(Request $request){
        /*echo __METHOD__;
        echo '<pre>';print_r($_POST);echo '</pre>';*/
        if($request->input('pwd')!=$request->input('pwds')){
            die('密码与确认密码不同');
        }else if($request->input('name')==''){
            die('用户名不能为空');
        }else if($request->input('pwd')==''){
            die('密码不能为空');
        }else if($request->input('email')==''){
            die('邮箱不能为空');
        }
        $pwd=$request->input('pwd');
        $data = [
            'name'  => $request->input('name'),
            'pwd'  => password_hash($pwd,PASSWORD_BCRYPT),
            'email'  => $request->input('email'),
            'time'  => time(),
        ];
        $res = RequestModel::insertGetId($data);
//        var_dump($res);
        if($res){
            header("Refresh:3;url=/login");
            echo "<h1 style=\"margin-left:45%\" class=\"text-primary\">注册成功</h1>";
        }else{
            echo '注册失败';
        }
    }

    /**
     * 登陆
     */
    public function login(){
        $result=urlencode("http://laravel.myloser.club/result");
        $scode="snsapi_userinfo";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WEIXIN_APPID')."&redirect_uri=$result&response_type=code&scope=$scode&state=STATE#wechat_redirect";
//        $data = [
//            'title'     => '登陆',
//        ];
        return view('user/login',['url'=>$url]);
    }
    public function result(Request $request){
        $arr=$request->input();
        $code=$arr['code'];
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WEIXIN_APPID')."&secret=".env('WEIXIN_APPSECRET')."&code=$code&grant_type=authorization_code";
        $info=file_get_contents($url);
        $arr=json_decode($info,true);
        $access_token=$arr['access_token'];
        $openid=$arr['openid'];
        $user_url="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $data = json_decode(file_get_contents($user_url),true);
        $openid=$data['openid'];
        $nickname=$data['nickname'];
        $sex=$data['sex'];
        $city=$data['city'];
        $province=$data['province'];
        $country=$data['country'];
        $headimgurl=$data['headimgurl'];
        $privilege=$data['privilege'];
        $where=[
            'openid'=>$openid
        ];
        $data_info=WechatUserOuick::where($where)->first();
        if(empty($data_info)){
            $data_info=[
                'openid'=>"$openid",
                'nickname'=>"$nickname",
                'sex'=>"$sex",
                'city'=>"$city",
                'province'=>"$province",
                'country'=>"$country",
                'headimgurl'=>"$headimgurl",
                'privilege'=>"",
            ];
            $res = WechatUserOuick::insertGetId($data_info);
            if($res){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('openid',$data_info['openid'],time()+86400,'/','',false,true);
                setcookie('token',$token,time()+86400,'/logins','',false,true);

                $request->session()->put('u_token',$token);
                $request->session()->put('openid',$data_info['openid']);
                $this->redis_info($data_info);
            }else{
                die("登陆失败");
            }

        }else{
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('openid',$data_info['openid'],time()+86400,'/','',false,true);
            setcookie('token',$token,time()+86400,'/logins','',false,true);

            $request->session()->put('u_token',$token);
            $request->session()->put('openid',$data_info['openid']);
            $this->redis_info($data_info);
        }
    }
    /**
     * 存入redis
     */
    public function redis_info($data){
        $obj=new \redis();
        $obj->connect('127.0.0.1',6379);
        $id=$obj->incr('id');
        $key="id_$id";

        $obj->hset($key,'id',$id);
        $obj->hset($key,'openid',$data['openid']);
        $obj->hset($key,'nickname',$data['nickname']);
        $obj->hset($key,'sex',$data['sex']);
        $obj->hset($key,'city',$data['city']);
        $obj->hset($key,'province',$data['province']);
        $obj->hset($key,'country',$data['country']);
        $obj->hset($key,'headimgurl',$data['headimgurl']);
        $obj->hset($key,'time',time());

        $list="per_id";
        $obj->rpush($list,$key);
        if(!empty($obj->lrange($list,0,-1))){
            echo "登陆成功，正在跳转";
                header("Refresh:3;url=/index");
        };
    }
    /**
     * 登陆
     */
    public function loginAdd(Request $request){
        $name=$request->input('name');
        $pwd=$request->input('pwd');
        $where=[
            'name'=>$name
        ];
        $data=RequestModel::where($where)->first();
        if(empty($data)){
            die('用户不存在');
        }
        $pwd_info=$data['pwd'];
        $pwd_res=password_verify($pwd,$pwd_info);
        if(!$pwd_res){
            die('密码错误');
        }else{
            echo "登陆成功，正在跳转";
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$data['u_id'],time()+86400,'/','',false,true);
            setcookie('token',$token,time()+86400,'/login','',false,true);

            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$data->id);
            header("Refresh:3;url=/index");
        }
    }

    /**
     * 退出
     */
    public function exits(){
        header("Refresh:3;url=/index");
        echo "正在退出，请稍后";
    }
}
