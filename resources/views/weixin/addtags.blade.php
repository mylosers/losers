<form class="form-inline" method="post">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="form-group">
        <p class="form-control-static">标签名</p>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="输入标签名" id="name">
    </div>
    <button type="button" class="btn btn-default" id="add">创建</button>
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
                if(name==""){
                    return false;
                }
                $.ajax({
                    type:"POST",
                    url:"http://laravel.myloser.club/admin/wechat/addWxTags",
                    data:{"name":name},
                    success:function(msg){
                        if(msg==1){
                            window.location.href='http://laravel.myloser.club/admin/wechat/wxTag';
                        }
                    }
                })
            });
            return false;

        });
</script>