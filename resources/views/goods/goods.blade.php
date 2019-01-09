@extends('layout.bst')

@section('content')
    <div class="container">
        <h1>{{$goods->goods_name}}</h1>

        <span> 价格： {{$goods->price / 100}}</span>


        <form class="form-inline">
            <div class="form-group">
                <label class="sr-only" for="goods_num">Amount (in dollars)</label>
                <div class="input-group">
                    <select class="form-control">
                        <option>1</option>
                    </select>
                </div>
            </div>
            <input type="hidden" id="goods_id" value="{{$goods->goods_id}}">
            <button type="submit" class="btn btn-primary" id="add_cart_btn">加入购物车</button>
        </form>
    </div>

    
@endsection

@section('footer')
    @parent
@endsection