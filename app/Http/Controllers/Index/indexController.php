<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\barrageModel;

use App\Model\GoodsModel;

class IndexController extends Controller
{
    public function index(){
       return view('index.index');
    }

    public function barrage(){
        return view('index.barrage');
    }

    public function timeInfo(){
        $user = $_POST['user'];
        $text = $_POST['text'];
        $u=barrageModel::where(['user'=>$user,'text'=>$text])->first();
        if($u){

        }else{
            $data=[
                'user'=>$user,
                'text'=>$text
            ];
            $info=barrageModel::insertGetId($data);
            if($info){
                $list=barrageModel::get()->toArray();
                $str="";
                foreach($list as $k=>$v){
                    $str.="<tr>".
                    "<td>".$v['id']."</td>".
                    "<td>".$v['user']."</td>".
                    "<td>".$v['text']."</td>".
                    "</tr>";
                }
                return $str;
            }
        }
    }

    public function barrageList(){
        $list=barrageModel::get()->toArray();
        if($list){
           return view('index.barragelist',['data'=>$list]);
        }
    }
}