<?php
namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Weixin\WXBizDataCryptController;
use App\Model\OrderModel;
class   WxpayController extends Controller{
    public $weixin_unifiedorder_url='https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $weixin_notify_url='http://1807.96myshop.cn/weixin/notice'; //支付通知回调

    public function firtest(){
        $total_fei=1;  //支付金额
        $order_id=OrderModel::generateOrderSN();  //订单号
        $order_info=[
            'appid'  => env('WEIXIN_APPID_0'),      //微信绑定服务号的APPID
            'mch_id' => env('WEIXIN_MCH_ID'),       //商户id
            'nonce_str' =>str_random('16'),       //随机字符串
            'sign_type' =>'MD5',                          //
            'body'      =>'枪神测试订单'.mt_rand(1111,9999).str_random(6),
            'out_order_no' =>$order_id,                   //订单号
            'total_fee'    =>$total_fei,                  //支付金额
            'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],  //客户端IP
            'notify_url'         => $this->weixin_notify_url,//通知回调地址
            'trade_type'         => 'NATIVE'                  //支付类型
        ];
//        var_dump($order_info);die;
        $this->values=[];
        $this->values=$order_info;
        $this->SetSign();
        $xml=$this->Toxml();   //将数组转换为html
//        var_dump($xml);die;
        $rs =$this->postXmlCurl($xml,$this->weixin_unifiedorder_url,$useCert=false,$second=30);
        var_dump($rs);die;
        $data=simplexml_load_string($rs);
        var_dump($data);die;
        echo 'code_url:'.$data->code_url;echo '<br>';
    }
    public function SetSign(){
        $sign=$this->makeSign();
        $this->values['sign']=$sign;
        return $sign;
    }
    private function makeSign(){
        //签名部署一:按字典序排序参数
        ksort($this->values);
        $string=$this->ToUrlParams();
        //签名部署二:在string后加入key
        $string=$string."&key=".env('WEIXIN_MCH_KEY');
        //签名部署三:MD5加密
        $string =md5($string);
        //签名部署四:所有字符转为大写
        $result=strtoupper($string);
        return $result;
    }
    /*
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams(){
        $buff = "";
        foreach($this->values as $k =>$v){
            if($k!="sign" && $v!=""&&!is_array($v)){
                $buff .=$k ."=".$v."&";
            }
        }
        $buff=trim($buff,"&");
        return $buff;
    }
    protected function Toxml(){
        //is_array 判断变量类型是否为数组类型
        if(!is_array($this->values)|| count($this->values)<=0){
            die("数组数据异常!");
        }
        $xml="<xml>";
        foreach($this->values as $key=>$val){
            if(is_numeric($val)){
                $xml .="<".$key.">".$val."</".$key.">";
            }else{
                $xml .="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .="</xml>";
        return $xml;
    }
    private function postXmlCurl($xml,$url,$useCert=false,$second=30){
        $ch =curl_init();   //初始化一个CURL对象
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);   //设置超时
        curl_setopt($ch,CURLOPT_URL,$url);   //设置你所需要抓取的URL
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);   //严格校验
        curl_setopt($ch,CURLOPT_HEADER,FALSE);       //设置header
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);    //要求结果为字符串且输出到屏幕上
        //post提交方式
        curl_setopt($ch,CURLOPT_POST,TRUE);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
        //运行curl
        $data=curl_exec($ch);
//        var_dump($data);die;
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        }else{
            $error=curl_errno($ch);
            curl_close($ch);
            die("curl错误,错误码:$error");
        }
    }
    /*
     * 微信支付回调
     */
    public function notice(){
        $data=file_get_contents("php://input");
        //记录日志
        $log_str=date('Y-m-d H:i:s')."\n".$data."\n<<<<<<<";
        file_put_contents('logs/wx_pay_notice.log',$log_str,FILE_APPEND);
        $xml=simplexml_load_string($data);
        if($xml->result_code=='SUCCESS' && $xml->return_code=='SUCCESS'){       //微信支付成功回调
            //验证签名
            $sign=true;
            if($sign){      //签名验证成功
                //TODO逻辑处理 订单状态更新
            }else{
                echo '验签失败,IP:'.$_SERVER['REMOTE_ADDR'];
            }
        }
        $response='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;
    }
}
?>