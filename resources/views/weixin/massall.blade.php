<table class="table table-striped">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <tr>
        <td>选择群发</td>
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
<form class="form-horizontal">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">选择发送类型</label>
        <div class="col-sm-10">
            <select class="form-control" id="select">
                <option value="text">文本</option>
                <option value="mpnews">图文消息</option>
                <option value="voice">语音</option>
                <option value="image">图片</option>
                <option value="mpvideo">视频</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">发送内容</label>
        <div class="col-sm-10">
            <div id="tihuan">
                <input type="text" class="form-control" id="inputPassword3">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-primary submit">发送</button>
        </div>
    </div>
</form>
<script>
    $(function(){
        $("#select").change(function(){
            if($("#select option:selected").val()=="text"){
                var str_text=''+
                                '<div id="tihuan">'+
                                '<input type="text" class="form-control" id="inputPassword3">'+
                                '</div>';

                $("#tihuan").replaceWith(str_text);
            }else if($("#select option:selected").val()=="mpnews"){
                var str_mpnews=''+
                        '<div id="tihuan">'+
                        '<select class="form-control">'+
                        '<option>1</option>'+
                        '<option>a</option>'+
                        '</select>'+
                        '</div>';
                $("#tihuan").replaceWith(str_mpnews);
            }else if($("#select option:selected").val()=="voice"){
                var str_voice=''+
                        '<div id="tihuan">'+
                        '<select class="form-control">'+
                        '<option>2</option>'+
                        '<option>b</option>'+
                        '</select>'+
                        '</div>';

                $("#tihuan").replaceWith(str_voice);
            }else if($("#select option:selected").val()=="image"){
                var str_image=''+
                        '<div id="tihuan">'+
                        '<select class="form-control">'+
                        '<option>3</option>'+
                        '<option>c</option>'+
                        '</select>'+
                        '</div>';

                $("#tihuan").replaceWith(str_image);
            }else if($("#select option:selected").val()=="mpvideo"){
                var str_mpvideo=''+
                        '<div id="tihuan">'+
                        '<select class="form-control">'+
                        '<option>4</option>'+
                        '<option>d</option>'+
                        '</select>'+
                        '</div>';

                $("#tihuan").replaceWith(str_mpvideo);
            }
        })

        $(".submit").click(function(){
            openid = [];
            $("input[name='vehicle']:checked").each(function(i){
                openid[i] =$(this).val();
            });
            var type=$("#select option:selected").val();
            if(type=="text"){
                var media_id=$("#inputPassword3").val()
            }else{
                var media_id=$("#tihuan select option:selected").val()
            }
            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/MassAllAdd",
                data:{"openid":openid,"media_id":media_id,"type":type},
                success:function(msg){
                    if(msg=="ok"){
                        alert('发送成功')
                    }
                }
            })
        })
    })
</script>
