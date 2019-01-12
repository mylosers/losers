<?php

namespace App\Http\Controllers\User;
use App\Model\RequestModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class UserController
 * @package App\Http\Controllers\User
 * 用户控制器
 */
class UserController extends Controller
{
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
        $data = [
            'title'     => '登陆',
        ];
        return view('user/login',$data);
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
            echo "登陆成功";
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$data['u_id'],time()+86400,'/','',false,true);
            setcookie('token',$token,time()+86400,'/login','',false,true);

            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$data->id);
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
