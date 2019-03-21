<script src="http://laravel.myloser.club/js/ajaxfileupload.js"></script>
<div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="file" name="file">
</div>
<script>
    $(function(){
        $("#file").change(function(){
            $.ajaxFileUpload({
                type:"post",
                url:"http://laravel.myloser.club/admin/wechat/upload",
                secureuri:false,
                fileElementId:'file',
                dataType:"json",
                success:function(msg){
                    console.log(msg);
//                    var filePath=msg.path;
//                    $("#exampleInputFile").append("<img src="+filePath+">");
                }
            })
        })
    })
</script>