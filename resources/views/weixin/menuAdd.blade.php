<button type="button" class="btn btn-default btn-lg btn-block add">创建菜单</button>
<button type="button" class="btn btn-primary btn-lg btn-block submit">提交菜单</button>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    $(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".submit").click(function(){
            var namesize=$(".name").size();
            var obj=[];
            for (var i=0; i<namesize; i++)
            {
                var name=$(".name").eq(i).val();
                var select=$(".select").eq(i).val();
                obj.push({name:name,select:select})
            }

            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/menuInfo",
                data:{"obj":obj},
                success:function(msg){
                    console.log(msg)
                }
            })

        });

        //创建一级菜单
        $(".add").click(function(){
            menu("一",1);
            $(".submit").before(str);
            var s=$(".one").text();
            var reg = new RegExp("一级菜单", "g");
            if(s.match(reg).length==3){
                $(".add").attr("disabled","disabled");
            }

        });
        //创建二级菜单
        $(document).on('click', '.twoAdd', function(){
            menu("二","");
            if($(this).parents("form").find(".form-inline").size()>4){
                $(this).attr("disabled","disabled");
            }else{
                $(this).parents('.form-group').after(str);
                $(this).parents("form").find(".form-inline").find(".twoAdd").attr("class","hidden");
               $(this).parent().find('select option').attr("value","");
                $(this).parent().find('select').hide();
            }
//            $(this).parents("form").next().next().find(".twoAdd").attr("class","hidden");
        });
        //删除菜单
        $(document).on("click",".delete",function(){
            if($(this).parent().parent().parent().find(".form-inline").size()==1){
                $(this).parent().parent().parent().find("select").show();
            }
            $(this).parent().parent().parent().find($("[disabled='disabled']")).removeAttr("disabled");
            $(this).parent().parent().remove();
        });
        var w=0,tip=$("<b>");
        tip.css({
            "z-index":99999,position:"absolute",color:"red",display:"none"
        }),
                $("body").append(tip),//页面创建b标签用来显示数字
                $(document).on("click",function(e){
                    var x=e.pageX,y=e.pageY;//获取点击页面坐标
                    tip.text("+"+ ++w).css({//数字加1
                        display:"block",top:y-15,left:x,opacity:1//定位显示
                    }).stop(!0,!1).animate({//stop(stopAll,goToEnd),如果发生多次点击时，要停止上一个动画的执行
                        top:y-180,opacity:0},800,function(){
                        tip.hide()
                    }),
                            e.stopPropagation()
                });
        function menu(one,status){
            str=''+
                    '<form class="form-inline">'+
                        '<div class="form-group">'+
                            '<div class="input-group">'+
                            '<div class="input-group-addon one">'+one+'级菜单</div>'+
                            '<input type="text" class="form-control name" id="exampleInputAmount" placeholder="">'+
                            '<div class="input-group-addon type">菜单类型</div>'+
                            '<select class="form-control select">'+
                                '<option value="location_select'+status+'">location_select(发送位置)</option>'+
                                '<option value="click'+status+'">click(点击)</option>'+
                                '<option value="view'+status+'">view(跳转)</option>'+
                                '<option value="scancode_waitmsg'+status+'">scancode_waitmsg(扫码带提示)</option>'+
                                '<option value="scancode_push'+status+'">scancode_push(扫码推事件)</option>'+
                                '<option value="pic_sysphoto'+status+'">pic_sysphoto(系统拍照发图)</option>'+
                                '<option value="pic_photo_or_album'+status+'">pic_photo_or_album(拍照或者相册发图)</option>'+
                                '<option value="pic_weixin'+status+'">pic_weixin(微信相册发图)</option>'+
                            '</select>'+
                            '</div>'+
                            '<button type="button" class="btn btn-info twoAdd">创建二级菜单</button>'+
                            '<button type="button" class="btn btn-warning delete">删除菜单</button>'+
                        '</div>'+
                    '</form>';
           return str;
        }
    })
</script>
<style>
    .show {
        display: block !important;
    }
    .hidden {
        display: none !important;
    }
    .invisible {
        visibility: hidden;
    }

       .element {
       .show();
       }
    .another-element {
    .hidden();
    }
</style>