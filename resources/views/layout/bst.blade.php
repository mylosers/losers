<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BootStrap</title>

    <link rel="stylesheet" href="http://laravel.myloser.club/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/index">首页</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/goodsList">商品列表</a></li>
                    <li><a href="/goods">购物车</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">个人中心 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/order">我的订单</a></li>
                            <li><a href="#">待收货</a></li>
                            <li><a href="/user">个人中心</a></li>
                            <li><a href="/orderListAdd">支付订单列表</a></li>
                            <li><a href="/numGoods">浏览记录列表</a></li>
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                    <li><a href="/request">注册</a></li>
                    <li><a href="/login">登录</a></li>
                    <li><a href="/exit">退出</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
    @yield('content')
</div>

@section('footer')
    <script src="http://laravel.myloser.club/js/jquery-3.3.1.min.js"></script>
    <script src="http://laravel.myloser.club/js/bootstrap.min.js"></script>
@show
</body>
</html>