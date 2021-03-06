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
                                <textarea id="demo" name="text" readonly>{$data.present}</textarea>
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