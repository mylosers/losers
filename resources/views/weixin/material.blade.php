<table class="table table-striped">
    <tr>
        <td>id</td>
        <td>media_id</td>
        <td>filepath</td>
        <td>time</td>
    </tr>
    @foreach($res as $k=>$v)
    <tr>
        <td>{{$v['id']}}</td>
        <td>{{$v['media_id']}}</td>
        <td><img src="http://laravel.myloser.club{{$v['filepath']}}" style="width: 100px"></td>
        <td>{{$v['time']}}</td>
    </tr>
    @endforeach
    <div class="paging">
        <a href="/admin/wechat/materialList?page={{$data['first']}}">首页</a>
        <a href="/admin/wechat/materialList?page={{$data['toppage']}}">上一页</a>
        <a href="/admin/wechat/materialList?page={{$data['nexpage']}}">下一页</a>
        <a href="/admin/wechat/materialList?page={{$data['weiye']}}">尾页</a>
    </div>
</table>