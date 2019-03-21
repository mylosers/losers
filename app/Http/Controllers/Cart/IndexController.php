<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\GoodsModel;

class IndexController extends Controller
{

    public $uid;                    // 登录UID


    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->uid = session()->get('uid');
            return $next($request);
        });

    }
    //
    public function index(Request $request)
    {
        $cart_goods = CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if(empty($cart_goods)){
            header('Refresh:2;url=/goodsList');
            echo ("购物车是空的，正在跳转");
            die;
        }

        //echo '<pre>';print_r($cart_goods);echo '</pre>';echo '<hr>';
        $total = 0;
        if($cart_goods){
            //获取商品最新信息
            foreach($cart_goods as $k=>$v){
                $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $goods_info['num']  = $v['num'];
                //echo '<pre>';print_r($goods_info);echo '</pre>';
                $list[] = $goods_info;
                $total += $goods_info['goods_selfprice'] * $v['num'];
            }
        }

        $data = [
            'list'      => $list,
            'total'     => $total
        ];
        return view('goods.cart',$data);

    }


    //商品列表
    public function goodsList(){
        $goods = GoodsModel::where(['goods_new'=>1])->get()->toArray();
        $data=[
            'data'=>$goods
        ];
        return view('goods.goodslist',$data);
    }

    /**
     * 添加商品
     */
    public function add($goods_id)
    {

        $cart_goods = session()->get('cart_goods');
        //是否已在购物车中
        if(!empty($cart_goods)){
            if(in_array($goods_id,$cart_goods)){
                header('Refresh:2;url=/goodsList');
                echo '已存在购物车中';
                exit;
            }
        }

        session()->push('cart_goods',$goods_id);

        //减库存
        $where = ['goods_id'=>$goods_id];
        $store = GoodsModel::where($where)->value('goods_stock');
        if($store<=0){
            echo '库存不足';
            exit;
        }
        $rs = GoodsModel::where(['goods_id'=>$goods_id])->decrement('goods_stock');

        if($rs){
            echo '添加成功';
        }

    }

    /**
     * 判断库存
     */
    public function number(Request $request){
        $goods_id = $request->input('goods_id');
        $goods_stock = $request->input('goods_stock');
        $store_num = GoodsModel::where(['goods_id'=>$goods_id])->value('goods_stock');
        if($store_num<$goods_stock){
            return 1;
        }else{
            return 2;
        }
    }

    /**
     *购物车页面
     */
    public function goods($goods_id){
        $goods = GoodsModel::where(['goods_id'=>$goods_id])->first();

        //商品不存在
        if(!$goods){
            header('Refresh:2;url=/');
            echo '商品不存在,正在跳转至首页';
            exit;
        }

        $data = [
            'goods' => $goods
        ];
        return view('goods.goods',$data);

    }

    /**
     * 购物车添加商品
     * @return array
     */
    public function goodsAdd(Request $request)
    {
        $goods_id = $request->input('goods_id');
        $num = $request->input('num');

        //检查库存
        $store_num = GoodsModel::where(['goods_id'=>$goods_id])->value('goods_stock');
        if($store_num<=0){
            $response = [
                'errno' => 5001,
                'msg'   => '库存不足'
            ];
            return $response;
        }

        //检查购物车重复商品
        $cart_goods = CartModel::where(['uid'=>$this->uid])->get()->toArray();
        if($cart_goods){
            $goods_id_arr = array_column($cart_goods,'goods_id');

            if(in_array($goods_id,$goods_id_arr)){
                $response = [
                    'errno' => 5002,
                    'msg'   => '商品已在购物车中，请勿重复添加'
                ];
                return $response;
            }
        }
        //写入购物车表
        $data = [
            'goods_id'  => $goods_id,
            'num'       => $num,
            'add_time'  => time(),
            'uid'       => $this->uid,
            'session_token' => session()->get('u_token')
        ];

        $cid = CartModel::insertGetId($data);
        if(!$cid){
            $response = [
                'errno' => 5002,
                'msg'   => '添加购物车失败，请重试'
            ];
            return $response;
        }


        $response = [
            'error' => 0,
            'msg'   => '添加成功'
        ];
        return $response;
    }

    /**
     * 删除商品
     */
    public function del($goods_id)
    {
        $rs = CartModel::where(['uid'=>$this->uid,'goods_id'=>$goods_id])->delete();
        //echo '商品ID:  '.$abc . ' 删除成功1';
        if($rs){
            header('Refresh:2;url=/goods');
            echo '商品ID:  '.$goods_id . ' 删除成功，正在跳转';
        }else{
            echo '商品ID:  '.$goods_id . ' 删除失败';
        }
    }




}
