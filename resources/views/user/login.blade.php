{{--@extends('layout.bst')

@section('content')--}}
    {{--<h1 style="margin-left:45%" class="text-primary">{{$title}}页面</h1>
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
    </form>--}}
    <html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="Xl3WlJo8vgrLcR65BownN4Z3NNOmOvlCXFLU7ffH">

        <title>Laravel</title>

        <!-- Scripts -->
        <script src="http://laravel.myloser.club/js/app.js" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="http://laravel.myloser.club/css/app.css" rel="stylesheet">
    </head>
    <body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="http://laravel.myloser.club/index.php">
                    Laravel
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="http://laravel.myloser.club/index.php/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="http://laravel.myloser.club/index.php/register">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Login</div>

                            <div class="card-body">
                                <form method="POST" action="http://laravel.myloser.club/index.php/login" aria-label="Login">
                                    <input type="hidden" name="_token" value="Xl3WlJo8vgrLcR65BownN4Z3NNOmOvlCXFLU7ffH">
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" value="" required autofocus>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" required>

                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6 offset-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember" >

                                                <label class="form-check-label" for="remember">
                                                    Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                Login
                                            </button>

                                            <a class="btn btn-link" href="http://laravel.myloser.club/index.php/password/reset">
                                                Forgot Your Password?
                                            </a>
                                            <a href="{{$url}}"><button type="button" class="btn btn-info" id="three">第三方登陆（微信）</button></a>
                                            <a href="http://laravel.myloser.club/wechat/code"><button type="button" class="btn btn-info">扫码登陆</button></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    </body>
    </html>
{{--

@endsection

@section('footer')
    @parent
@endsection--}}
