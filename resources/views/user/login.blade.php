@extends('layout.mama')

@section('title') {{$title}}    @endsection

@section('header')
    @parent
@endsection

@section('content')
    <h1 style="margin-left:45%" class="text-primary">{{$title}}页面</h1>
    <br>
    <form action="/loginAdd" method="post">
        @csrf
        <table class="table table-striped">
            <thead>
            <tr>
                <td>
                    <input type="name" class="form-control" id="exampleInputEmail1" placeholder="用户名" name="name">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="password" class="form-control" id="exampleInputEmail1" placeholder="密码" name="pwd">
                </td>
            </tr>
            </thead>
        </table>
        <br>
        <button style="margin-left: 48%;" class="btn btn-primary btn-lg">登陆</button>
    </form>
@endsection


@section('footer')
    @parent
@endsection