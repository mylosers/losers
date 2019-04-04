<div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>

    <div class="input-group">
        <div class="input-group-addon">客户列表</div>
        <select class="form-control" id="select">
            <option></option>
            @foreach($data as $k=>$v)
                <option>{{$v}}</option>
            @endforeach
        </select>
    </div>
    <br>

    <div style="width: 100%;height:350px;background:#cecece;overflow:auto">
        <ul id="ul">

        </ul>
    </div>
    <br>
    <input type="text" class="form-control text" placeholder="输入消息">
    <button type="button" class="btn btn-primary btn-lg btn-block button">发送</button>
</div>
<script>
    $(function () {
        $("#select").change(function () {
            $("#ul").empty();
            var openid = $("#select option:selected").text();
            if (openid == "") {
                alert("无客户消息");
                return false;
            }
            $.ajax({
                type: "POST",
                url: "http://laravel.myloser.club/admin/wechat/testList",
                data: {"openid": openid},
                success: function (msg) {
                    if (msg) {
                        if (msg == "") {
                            str = "" +
                                    "<li>暂无聊天记录</li>";
                            $("#ul").html(str);
                        } else {
                            for (var i = 0; i <= msg.length; i++) {
                                var new_str = "";
                                var newDate = new Date();
                                var c_time = msg[i]['c_time'];
                                var text = msg[i]['text'];
                                newDate.setTime(c_time * 1000);
                                if (msg[i]['status'] == 1) {
                                    new_str += "" +
                                            "<li>" + newDate.toLocaleString() + " — — 客服</li>" +
                                            "<b>" + text + "</b>";
                                } else {
                                    new_str += "" +
                                            "<li>" + newDate.toLocaleString() + " — — 用户</li>" +
                                            "<b>" + text + "</b>";
                                }
                                $("#ul").append(new_str);
                            }
                        }
                    }
                }
            })
        });

        //发送消息
        $(".button").click(function () {
            $("#ul").empty();
            var text = $(".text").val();
            var openid = $("#select option:selected").text();
            if (openid == "") {
                alert("无客户消息");
                return false;
            }
            $.ajax({
                type: "POST",
                url: "http://laravel.myloser.club/admin/wechat/testAdd",
                data: {"openid": openid, "text": text},
                success: function (msg) {
                    if (msg) {
                        if (msg == "ok") {
                            $(".text").val("");
                            $.ajax({
                                type: "POST",
                                url: "http://laravel.myloser.club/admin/wechat/testList",
                                data: {"openid": openid},
                                success: function (msg) {
                                    if (msg) {
                                        if (msg == "") {
                                            str = "" +
                                                    "<li>暂无聊天记录</li>";
                                            $("#ul").html(str);
                                        } else {
                                            for (var i = 0; i <= msg.length; i++) {
                                                var new_str = "";
                                                var newDate = new Date();
                                                var c_time = msg[i]['c_time'];
                                                var text = msg[i]['text'];
                                                newDate.setTime(c_time * 1000);
                                                if (msg[i]['status'] == 1) {
                                                    new_str += "" +
                                                            "<li>" + newDate.toLocaleString() + " — — 客服</li>" +
                                                            "<b>" + text + "</b>";
                                                } else {
                                                    new_str += "" +
                                                            "<li>" + newDate.toLocaleString() + " — — 用户</li>" +
                                                            "<b>" + text + "</b>";
                                                }
                                                $("#ul").append(new_str);
                                            }
                                        }
                                    }
                                }
                            })
                        }
                    }
                }
            })
        })
    })
</script>