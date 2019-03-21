<table class="table table-striped">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <tr>
        <td>黑名单用户</td>
        <td style="font-weight:bold;">openid</td>
    </tr>
    @if($data=="")
        <tr>
            <td></td>
            <td></td>
        </tr>
    @else
        @foreach($data as $k=>$v)
            <tr>
                <td><input type="checkbox" class="checkbox" value="{{$v}}" name="vehicle"></td>
                <td>{{$v}}</td>
            </tr>
        @endforeach
    @endif
</table>
<button type="button" class="btn btn-info on">全选</button>
<button type="button" class="btn btn-info off">全不选</button>
<button type="button" class="btn btn-danger black">取消拉黑</button>
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".on").click(function(){
            $(".checkbox").prop("checked",true);
        });
        $(".off").click(function(){
            $(".checkbox").prop("checked",false);
        });
        $(".black").click(function(){
            var checkID = [];
            $("input[name='vehicle']:checked").each(function(i){
                checkID[i] =$(this).val();
            });
            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/WxBlackUserOff",
                data:{"openid":checkID},
                success:function(msg){
//                    console.log(msg);
                    if(msg=="ok"){
                        window.location.href='http://laravel.myloser.club/admin/wechat/WxBlackUserList';
                    }else{
                        alert('错误');
                    }
                }
            })
        })
    })
</script>