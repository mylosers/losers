<?php
/**
 * Created by PhpStorm.
 * User: 31916
 * Date: 2018/12/29
 * Time: 9:06
 */

namespace App\Http\Controllers\Order;

use App\Model\UserModel;
use Illuminate\Routing\Controller;

class IndexController extends Controller
{

    public function detail($id)
    {
        echo $id;
        echo __METHOD__;
    }

    public function user($id){
        $man=UserModel::where(['id'=>$id])
            ->first()
            ->toArray();
        dd($man);
    }

    /**
     * 添加
     */
    public function insert(){
        $data=[
            'name'=>str_random(6),
            'age'=>rand(10,99),
        ];
        $res=UserModel::insert($data);
        if ($res){
            echo '添加成功';
        }
    }

    /**
     * 修改
     * @param $id
     */
    public function update($id){
        $data=[
            'name'=>str_random(6)
        ];
        $where=[
            'id'=>$id
        ];
        $res=UserModel::where($where)->update($data);
        if ($res){
            echo '修改成功';
        }
    }

    /**
     * 删除
     */
    public function delete($id){
        $res=UserModel::where(['id'=>$id])->delete();
        if ($res){
            echo '删除成功';
        }
    }

    /**
     * 查询
     */
    public function select(){
        $arr=UserModel::get();
        $data=[
            'arr'=>$arr,
            'page'=>rand(100,999),
        ];
        return view('index.select',$data);
    }

    /**
     * 参数
     */
    public function one(){
        var_dump($_POST);echo "<br>";
        var_dump($_GET);echo "<br>";

    }

}
