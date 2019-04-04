@extends('layout.bst')

@section('content')
    <div class="container">
        <table class="table table-striped">
            <tr>
                <td>商品名称</td>
                <td>商品价格</td>
                <td>浏览次数</td>
            </tr>
            @foreach($data as $k=>$v)
                <tr>
                    <td>{{$v['goods_name']}}  </td>
                    <td>¥ {{$v['goods_selfprice']}}</td>
                    <td>{{$v['num']}}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

@section('footer')
    @parent
@endsection