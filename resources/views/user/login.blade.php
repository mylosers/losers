@extends('layout.mama')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <h1 style="margin-left:45%">{{$title}}页面</h1>

    <form action="/loginAdd" method="post">
        @csrf
        <table border style="margin-left: 40%">
            <tr>
                <td>用户名</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input type="password" name="pwd"></td>
            </tr>
        </table><br>
        <button style="margin-left: 45%;width:100px;height:30px">登陆</button>
    </form>
@endsection


@section('footer')
    @parent
@endsection