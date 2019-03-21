<html>
<head>
    <title>Lening-@yield('title')</title>
</head>
<body>
@section('header')
    <link rel="stylesheet" href="css/bootstrap.min.css">
@show

<div class="container">
    @yield('content')

</div>

@section('footer')
@show
</body>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</html>