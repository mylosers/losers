{{-- 购物车 --}}
@extends('layout.bst')

@section('content')
    <div class="container">
        <table class="table table-striped">
            @foreach($list as $k=>$v)
            <tr>
                <td>{{$v['goods_id']}}    --  {{$v['goods_name']}}  -  ¥ {{$v['goods_selfprice']}}   --  {{date('Y-m-d H:i:s',$v['ctime'])}}</td>
                <td><a href="/goodsDel/{{$v['goods_id']}}" class="del_goods">删除</a></td>
            </tr>
            @endforeach
        </table>
        <h2>订单总额：¥ {{$total}}</h2>
        <hr>

        <a href="/orderAdd" id="submit_order" class="btn btn-info "> 提交订单 </a>
    </div>

@endsection

@section('footer')
    @parent
@endsection