<html>
<head>
    <title>Lening-@yield('title')</title>

    <link rel="stylesheet" href="{{URL::asset('/css/test.css')}}">
</head>
<body>

<div class="container">
    @yield('content')
</div>

@section('footer')
    <p style="color: blue">This is the main footer.</p>
    <script src="{{URL::asset('/js/test.js')}}"></script>
@show
</body>
</html>