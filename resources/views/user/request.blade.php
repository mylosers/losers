@extends('layout.mama')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <h1 style="margin-left:45%">{{$title}}页面</h1>
    <form action="/requestAdd" method="post">
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
            <tr>
                <td>确认密码</td>
                <td><input type="password" name="pwds"></td>
            </tr>
            <tr>
                <td>邮箱</td>
                <td><input type="email" name="email"></td>
            </tr>
        </table><br>
        <button style="margin-left: 45%;width:100px;height:30px">注册</button>
    </form>
@endsection


@section('footer')
    @parent
@endsection