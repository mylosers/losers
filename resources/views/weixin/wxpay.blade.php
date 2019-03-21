<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="http://laravel.myloser.club/js/qrcode.js"></script>
    <script src="http://laravel.myloser.club/js/jquery-3.3.1.min.js"></script>
</head>
<body>
   <div id="qrcode" align="center"></div>
</body>
</html>
<script>
    // 设置参数方式
    var qrcode = new QRCode('qrcode', {
        text:"you content",
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
    });
    // 使用 API
    qrcode.clear();
    qrcode.makeCode("{{$url}}");
    setInterval(function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:     '/weixin/pay/wxsuccess?order_id='+"{{$order_id}}",
            type:    'get',
            dataType: 'json',
            success:   function (d) {
                if(d.error == 0){
                    alert(d.msg);
                    location.href = '/order/list'
                }
            }
        });
    },5000)
</script>