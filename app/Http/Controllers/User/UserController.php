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
        echo __METHOD__;
        echo '<pre>';print_r($_POST);echo '</pre>';
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
            'pwd'  => md5($pwd),
            'email'  => $request->input('email'),
            'time'  => time(),
        ];
        $res = RequestModel::insertGetId($data);
//        var_dump($res);
        if($res){
            echo '注册成功';
            header('Location:/login');
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
        $pwd=md5($request->input('pwd'));
        $where=[
            'name'=>$name,
            'pwd'=>$pwd,
        ];
        $data = RequestModel::where($where)->first();
        if(empty($data)){
            die('用户名不存在或密码不正确！');
        }else{
            echo "登陆成功";
            header('Location:http://www.baidu.com');
        }
    }
}
