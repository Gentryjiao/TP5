<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>

    <!--    引入layedit-->
    <link href="/public/layedit/Content/Layui-KnifeZ/css/layui.css" rel="stylesheet" />
    <script src="/public/layedit/Content/Layui-KnifeZ/layui.js"></script>
    <script src="/public/layedit/Content/ace/ace.js"></script>
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
                            <label class="layui-form-label">姓名</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.name}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">生日</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.birthday}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">学历</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.education}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">用户昵称</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.username}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">头像</label>
                            <div class="layui-input-block">
                                <img src="{$data.image}" class="layui-upload-img" style="width:25%">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">性别</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.sex}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">创建时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.create_time}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">电子邮箱</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.email}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.num}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">求职状态</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{if $data.status_post==1}在求职{else/}未求职{/if}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">自我介绍</label>
                            <div class="layui-input-block">
                                <textarea id="container" name="text" readonly>{$data.present}</textarea>
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">就业状态</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{if $data.status==0}待业{else/}在业{/if}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">期望薪资</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.salary}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">期望地区</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.area}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">期望地区</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" value="{$data.area}" readonly autocomplete="off" class="layui-input">
                            </div>
                        </div>

<!--                        <div class="layui-form-item" style="max-width: 600px">-->
<!--                            <label class="layui-form-label">-->
<!--                                <button type="button" class="layui-btn layui-btn-sm" id="test">banner</button>-->
<!--                            </label>-->
<!--                            <div class="layui-input-block banner">-->
<!--                                <img id="banner" style="width:100%">-->
<!--                                <input type="hidden" name="banner">-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="layui-form-item">
                            <label class="layui-form-label">个人简介</label>
                            <div class="layui-input-block">
                                <textarea id="demo" name="text" readonly>{$data.introduction}</textarea>
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
<script type="text/javascript">
    layui.use(['layer','form','upload','layedit','laydate'],function(){
        var form = layui.form;
        var layer =layui.layer;
        var upload = layui.upload;
        var layedit = layui.layedit;
        var laydate = layui.laydate;

        layedit.set({
            //暴露layupload参数设置接口 --详细查看layupload参数说明
            uploadImage: {
                url: '/admin/image/layuiupload',
                accept: 'image',
                acceptMime: 'image/*',
                exts: 'jpg|png|gif|bmp|jpeg',
                size: '10240'
            }
            , uploadVideo: {
                url: '/admin/image/layuiupload',
                accept: 'video',
                acceptMime: 'video/*',
                exts: 'mp4|flv|avi|rm|rmvb',
                size: '204800'
            }
            //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
            //传递参数：
            //图片： imgpath --图片路径
            //视频： filepath --视频路径 imgpath --封面路径
            , calldel: {
                url: '/Attachment/DeleteFile'
            }
            //开发者模式 --默认为false
            , devmode: true
            //插入代码设置
            , codeConfig: {
                hide: true,  //是否显示编码语言选择框
                default: 'javascript' //hide为true时的默认语言格式
            },
            autoSync: true,
            tool: [
                'html'//源码模式
                ,'undo','redo' //撤销重做--实验功能，不推荐使用
                ,'code', 'strong', 'italic', 'underline', 'del'
                ,'addhr' //添加水平线
                ,'|','fontFomatt','fontfamily','fontSize' //段落格式，字体样式，字体颜色
                , 'colorpicker', 'fontBackColor'//字体颜色，字体背景色
                , 'face', '|', 'left', 'center', 'right', '|', 'link', 'unlink'
                ,'image'//原版上传图片
                ,'images'//多图上传
                , 'image_alt'//上传图片拓展
                , 'attachment'//上传附件
                , 'video' //视频上传
                ,'anchors' //锚点
                , '|', 'table'//插入表格
                ,'customlink'//插入自定义链接
                ,'fullScreen'//全屏
                ,'preview'//预览
            ],
            height: '400px'
        });
        var ieditor = layedit.build('demo');
        layedit.sync(ieditor);

        //layedit第三方文档
        // https://blog.knifez18.com/post/132412429623461713.html


        //图片上传
        upload.render({
            elem: '#test',
            url: "/admin/image/upload",
            done: function(res){
                $('#banner').attr('src',res.path);
                $('input[name="banner"]').val(res.path);
            }
        });

        //多图上传
        upload.render({
            elem: '#test',
            url: "/admin/image/upload",
            multiple: true,
            done: function(res){
                var html='<div style="float:left" class="del">'
                    +'<img src="'+ res.path +'" class="layui-upload-img" style="width:120px;height:120px">'
                    +'<input type="hidden" name="image[]" value="'+res.path+'">'
                    +'</div>';
                $('.image').append(html);
            }
        });
    })
</script>