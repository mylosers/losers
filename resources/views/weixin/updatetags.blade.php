<form class="form-inline" method="post">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="form-group">
        <p class="form-control-static">标签名</p>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="输入标签名" id="name">
        <input type="hidden" id="id" value="{{$id}}">
    </div>
    <button type="button" class="btn btn-default" id="add">修改</button>
</form>
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#add").click(function(){
            var name = $("#name").val();
            var id = $("#id").val();
            if(name==""){
                return false;
            }
            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/addUpdateWxTags",
                data:{"name":name,"id":id},
                success:function(msg){
                    if(msg=="ok"){
                        window.location.href='http://laravel.myloser.club/admin/wechat/wxTag';
                    }else{
                        alert('错误');
                    }
                }
            })
        });
        return false;

    });
</script>