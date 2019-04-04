@extends('layout.bst')

@section('content')
    <script src="http://laravel.myloser.club/js/jquery-3.3.1.min.js"></script>
    <form class="form-inline">
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
            <div class="input-group">
                <div class="input-group-addon">用户</div>
                <input type="text" class="form-control user" placeholder="user">
                <div class="input-group-addon">输入</div>
                <input type="text" class="form-control text" placeholder="barrage">

            </div>
        </div>
        <button type="button" class="btn btn-primary go">GO</button>
        <button type="button" class="btn btn-warning stop">STOP</button>
        <button type="button" class="btn btn-danger delete">DELETE</button>
    </form>
    <table class="table table-striped">
        <tr>
            <td>id</td>
            <td>user</td>
            <td>text</td>
        </tr>
            <tr id="tr">
                <td>1</td>
                <td>2</td>
                <td>3</td>
            </tr>
    </table>
    <script>
        $(function(){
            $(".stop").attr("disabled","disabled");
            //点击开始
            $(".go").click(function(){
                times();
                $(".go").attr("disabled","disabled");
                $(".stop").removeAttr("disabled");
                return false;
            });
            //点击结束
            $(".stop").click(function(){
                $(".go").removeAttr("disabled");
                $(".stop").attr("disabled","disabled");
                clearTimeout(timer);
            })
            //定时器
            function times(){
                timer=setInterval(function(){
                    var user=$(".user").val();
                    var text=$(".text").val();
                    if(user==""||text==""){
                        alert("不能为空");
                        $(".go").removeAttr("disabled");
                        $(".stop").attr("disabled","disabled");
                        clearTimeout(timer);
                        return false;
                    }
                    $.ajax({
                        type:"POST",
                        url:"http://laravel.myloser.club/timeInfo",
                        data:{"user":user,"text":text},
                        success:function(msg){
                            if(msg!=""){
                                $("#tr").after(msg);
                            }
                        }
                    })
                },1000);
            }
        })
    </script>
@endsection

@section('footer')
    @parent
@endsection
