<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\OrderModel;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        echo __METHOD__;
    }

    /**
     * 下单
     */
    public function add(Request $request)
    {
        //查询购物车商品
        $cart_goods = CartModel::where(['uid'=>session()->get('uid')])->orderBy('id','desc')->get()->toArray();
        if(empty($cart_goods)){
            header('Refresh:2;url=/goodsList');
            echo ("购物车中无商品");
        }
        $order_amount = 0;
        foreach($cart_goods as $k=>$v){
            $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $goods_info['num'] = $v['num'];
            $list[] = $goods_info;

            //计算订单价格 = 商品数量 * 单价
            $order_amount += $goods_info['goods_selfprice'] * $v['num'];
        }

        //生成订单号
        $order_sn = OrderModel::generateOrderSN();
        echo $order_sn;
        $data = [
            'order_sn'      => $order_sn,
            'uid'           => session()->get('uid'),
            'add_time'      => time(),
            'order_amount'  => $order_amount
        ];
        $oid = OrderModel::insertGetId($data);
        if(!$oid){
            echo '生成订单失败';
        }

        echo '下单成功,订单号：'.$oid .' 跳转支付';


        //清空购物车
        CartModel::where(['uid'=>session()->get('uid')])->delete();
        header("Refresh:3;url=/order");
    }


    /**
     * 订单列表
     */
    public function orderList()
    {
        $list = OrderModel::where(['uid'=>session()->get('uid'),'is_pay'=>0])->orderBy('oid','desc')->get()->toArray();
        $data = [
            'list'  => $list
        ];
        return view('goods.order',$data);
    }

    /**
     * 订单展示
     */
    public function orderListAdd(){
        $list = OrderModel::where(['is_pay'=>1])->get()->toArray();
        $data = [
            'list'  => $list
        ];
        return view('goods.orderlist',$data);

    }
}
