<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BootStrap</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
    @yield('content')
</div>

@section('footer')
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
@show
</body>
</html>