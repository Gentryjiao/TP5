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

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="date" id="date" lay-verify="date" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label"><button type="button" class="layui-btn layui-btn-ms" id="test">封面图</button></label>
                            <div class="layui-input-block image">
                                <img src="" id="image" style="width:50%">
                                <input type="hidden" name="image">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label"><button type="button" class="layui-btn layui-btn-ms" id="test2">视频</button></label>
                            <div class="layui-progress" lay-showpercent="true" lay-filter="demo">
                                <div class="layui-progress-bar" lay-percent="0%"></div>
                            </div>
                            <div class="layui-input-block video" style="display: none">
                                <video id="video" src="" controls="controls" style="width:100%"></video>
                                <input type="hidden" name="video">
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">介绍</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入介绍" class="layui-textarea" name="text" id="container" style="border:0;padding:0"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>
</html>
<!-- 配置文件 -->
<script type="text/javascript" src="/public/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/public/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>

<script>
    layui.use(['layer','form','upload','layedit','laydate','element'],function(){
        var form = layui.form;
        var layer =layui.layer;
        var upload = layui.upload;
        var laydate = layui.laydate;
        var element = layui.element;
        //日期
        laydate.render({
            elem: '#date'
        });

        layui.element.init();
        upload.render({
            elem: '#test',
            url: "/admin/image/upload",
            progress: function(n, elem){
                console.log(n);
                var percent = n + '%'; //获取进度百分比
                element.progress('demo', percent);
            },
            done: function(res){
                $('#image').attr('src',res.path);
                $('input[name="image"]').val(res.path);
            }
        });

        upload.render({
            elem: '#test2',
            url: "/admin/image/upload",
            accept:'video',
            progress: function(n, elem){
                var percent = n + '%'; //获取进度百分比
                element.progress('demo', percent);
            },
            done: function(res){
                $('.video').css('display','block');
                $('#video').attr('src',res.path);
                $('input[name="video"]').val(res.path);
            }
        });

        $(document).on('click','.del',function(){
            $(this).remove()
        })

    })
</script>