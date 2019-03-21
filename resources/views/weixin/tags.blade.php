<table class="table table-striped">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <tr>
        <td>tag_id</td>
        <td>tag_name</td>
        <td>count</td>
        <td>操作</td>
    </tr>
    @foreach($data as $k=>$v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td>
                <button type="button" class="btn btn-warning update">编辑</button>
                <button type="button" class="btn btn-danger delete">删除</button>
            </td>
        </tr>
    @endforeach
</table>
<style>
    td{
        font-weight:bold;
        text-align:center;
    }
</style>
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //删除标签
        $(".delete").click(function(){
            var id=$(this).parents('tr').find("td").first().text();
            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/deleteWxTags",
                data:{"id":id},
                success:function(msg){
                    if(msg=="ok"){
                        window.location.href='http://laravel.myloser.club/admin/wechat/wxTag';
                    }else{
                        alert('错误');
                    }
                }
            })
        });

        //修改标签
        $(".update").click(function(){
            var id=$(this).parents('tr').find("td").first().text();
            $.ajax({
                type:"POST",
                url:"http://laravel.myloser.club/admin/wechat/updateWxTags",
                data:{"id":id},
                success:function(msg){
                    document.write(msg);
                    document.close();
                }
            })
        });
    })
</script>
