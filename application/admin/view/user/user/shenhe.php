<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
    <style>
        .layui-form-item{
            margin-top:20px;width: 100%
        }
    </style>
</head>
<body class="gray-bg" style="margin:0px auto;padding:0px auto">
<form class="layui-form" id="form">

    <div style="padding:10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <input type="hidden" name="id" class="id" value="{$user.id}">

                        {volist name='card_image' id='img'}
                        <img src="{$img}" class="layui-upload-img" style="width:49%">
                        {/volist}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>

<script>
    layui.use(['form','layedit','upload'], function() {
        var form = layui.form;
        var upload = layui.upload;
        var layedit = layui.layedit;

    });

</script>
</html>