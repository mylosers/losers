@extends('layout.bst')

@section('content')

    <div class="container">
        <h1>{{$goods->goods_name}}</h1>

        <span> 价格： {{$goods->goods_selfprice}}</span>


        <form class="form-inline">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="form-group">
                <label class="sr-only" for="goods_num">Amount (in dollars)</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="goods_num" value="1">
                </div>
            </div>
            <input type="hidden" id="goods_id" value="{{$goods->goods_id}}">
            <button type="submit" class="btn btn-primary" id="add_cart_btn">加入购物车</button>
        </form>
    </div>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script>
        $('#goods_num').blur(function(){
            var number=$(this).val();
            var goods_id=$("#goods_id").val();
            if(number<=0){
                $(this).val(1);
            }else if(isNaN(number)){
                $(this).val(1);
            }else{
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'/number',
                    type:"post",
                    data:{goods_stock:number,goods_id:goods_id},
                    success:function(info){
                        if(info==1){
                            $("#goods_num").val({{$goods->goods_stock}});
                        }
                    }
                })
            }
        })
        //ajax提交
        $("#add_cart_btn").click(function(e){
            e.preventDefault();
            var num = $("#goods_num").val();
            var goods_id = $("#goods_id").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url     :   '/goodsAdd',
                type    :   'post',
                data    :   {goods_id:goods_id,num:num},
                dataType:   'json',
                success :   function(d){
                    if(d.error==0){
                        window.location.href='/goods';
                    }else{
                        alert(d.msg);
                    }
                }
            });
            return false;
        })
    </script>
@endsection

@section('footer')
    @parent
@endsection