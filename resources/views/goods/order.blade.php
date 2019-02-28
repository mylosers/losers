@extends('layout.bst')

@section('content')
    <div class="container">
        <h3>未支付订单：</h3>
        <table class="table table-striped">
            @foreach($list as $k=>$v)
            <tr>
                <td>订单ID: {{$v['order_sn']}} --  订单总价：¥{{$v['order_amount']}}   --  下单时间：{{date('Y-m-d H:i:s',$v['add_time'])}}</td>
                <td><a href="/pay/{{$v['oid']}}" class="btn btn-info">支付宝支付</a></td>
                <td><a href="/weixin/pay/test/{{$v['oid']}}" class="btn btn-info">微信支付</a></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection