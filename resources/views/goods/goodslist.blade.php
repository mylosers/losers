{{--商品列表 --}}
@extends('layout.bst')

@section('content')
    <div class="container">
        <table class="table table-striped">
            @foreach($data as $k=>$v)
                <tr>
                    <td>{{$v['goods_name']}}  </td>
                    <td>¥ {{$v['goods_selfprice']}}</td>
                    <td><a href="/goods/{{$v['goods_id']}}">查看商品</a></td>
                </tr>
            @endforeach
        </table>
        <hr>

    </div>

@endsection

@section('footer')
    @parent
@endsection