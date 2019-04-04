@extends('layout.bst')

@section('content')
<table class="table table-striped">
    <tr>
        <td>id</td>
        <td>user</td>
        <td>text</td>
    </tr>
        @foreach($data as $k=>$v)
            <tr>
                <td>{{$v['id']}}</td>
                <td>{{$v['user']}}</td>
                <td>{{$v['text']}}</td>
            </tr>
        @endforeach
</table>
@endsection

@section('footer')
    @parent
@endsection