<table class="table table-striped">
    <tr>
        <td>id</td>
        <td>openid</td>
        <td>key</td>
        <td>ticket</td>
        <td>c_time</td>
    </tr>
        @foreach($data as $k=>$v)
            <tr>
                <td>{{$v['id']}}</td>
                <td>{{$v['openid']}}</td>
                <td>{{$v['key']}}</td>
                <td>{{$v['ticket']}}</td>
                <td>{{$v['c_time']}}</td>
            </tr>
        @endforeach
</table>