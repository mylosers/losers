@extends('layout.bst')

@section('content')
    <h1 style="margin-left:45%" class="text-primary">{{$title}}页面</h1>
    <form action="/requestAdd" method="post">
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
            <tr>
                <td>
                    <input type="password" class="form-control" id="exampleInputEmail1" placeholder="确认密码" name="pwds">
                </td>
            </tr>
            <tr>
                    <td>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="邮箱" name="email">
                </td>
            </tr>
            </thead>
        </table><br>
        <button style="margin-left: 48%;" class="btn btn-primary btn-lg">注册</button>
    </form>

@endsection

@section('footer')
    @parent
@endsection