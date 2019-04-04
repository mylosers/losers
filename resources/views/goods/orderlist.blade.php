@extends('layout.bst')

@section('content')
    <div class="container">
        <h3>已支付订单：</h3>
        <table class="table table-striped">
            @foreach($list as $k=>$v)
                <tr>
                    <td>订单ID: {{$v['order_sn']}} --  订单总价：¥{{$v['order_amount']}}   --  下单时间：{{date('Y-m-d H:i:s',$v['add_time'])}}  —— 支付时间：{{date('Y-m-d H:i:s',$v['pay_time'])}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection