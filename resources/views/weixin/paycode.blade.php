@extends('layout.bst')

@section('content')
<script type="text/javascript" src="http://laravel.myloser.club/js/qrcode.min.js"></script>
<script type="text/javascript" src="http://laravel.myloser.club/js/jquery-3.3.1.min.js"></script>
<div id="qrcode"></div>
    <script>
        var qrcode = new QRCode('qrcode', {
            text: "{{$codeurl}}",
            width: 256,
            height: 256,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
@endsection

@section('footer')
    @parent
@endsection