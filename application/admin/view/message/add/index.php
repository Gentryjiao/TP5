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
<body class="gray-bg">
<form class="layui-form" id="form">
    <div style="padding: 0 15px 0 15px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">添加信息</div>
                    <div class="layui-card-body">
                        <a type="button" class="layui-btn layui-btn-sm" href="{:url('index')}">返回列表页</a>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">保险名</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" autocomplete="off" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item" style="max-width: 600px">
                            <label class="layui-form-label">保险分类</label>
                            <div class="layui-input-block">
                                <select name="pid" lay-filter="pid">
                                    <option value="1">信息分类</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <div class="layui-inline" style="margin-right: 0">
                                <label class="layui-form-label">年龄</label>
                                <div class="layui-input-inline" style="width: 130px;">
                                    <select class="age" name="age">
                                        <option value="1">10-20</option>
                                        <option value="2">21-30</option>
                                        <option value="3">31-40</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline" style="width: 100px">
                                <div class="layui-input-inline">
                                    <button type="button" class="layui-btn layui-btn-sm addage">添加</button>
                                </div>
                            </div>
                        </div>

                        <div class="zifu"></div>

                        <div class="layui-form-item layui-form-text" style="width: 1000px">
                            <label class="layui-form-label">特点</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入内容" class="layui-textarea" name="features" id="container" style="border:0;padding:0"></textarea>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">
                                <button type="button" class="layui-btn layui-btn-sm submit">提交</button>
                            </label>
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
    layui.use(['layer','form','upload','layedit','laydate'],function(){
        var form = layui.form;
        var layer =layui.layer;
        var upload = layui.upload;
        var layedit = layui.layedit;
        var laydate = layui.laydate;


        //添加年龄
        $(document).on('click','.addage',function(){
            var id=$(".age").val();
            var text= $("select[name='age']").find("option:selected").text();
            var html='<div class="zifue">'+
            '    <div class="layui-form-item" style="max-width: 600px">'+
            '        <button type="button" id="'+id+'" class="layui-btn layui-btn-sm jia" style="margin-bottom: 15px">添加自付额</button>'+
            '        <label class="layui-form-label">'+text+'</label>'+
            '        <div class="hang'+id+'"></div>'+
            '    </div>'+
            '</div>';
            $('.zifu').append(html);
        });

        //添加一行
        $(document).on('click','.jia',function () {
            var id=$(this).attr('id');
            var html='<div class="layui-input-block">\n' +
                '                <input type="hidden" name="ageid[]" value="'+id+'" autocomplete="off" class="layui-input">\n'+
                '                <input type="text" name="price'+id+'[]" placeholder="自付额" autocomplete="off" class="layui-input" style="width: 100px;display: inline">\n' +
                '                <input type="text" name="price'+id+'[]" placeholder="售价" autocomplete="off" class="layui-input" style="width: 100px;display: inline">\n' +
                '                <input type="text" name="price'+id+'[]" placeholder="底价" autocomplete="off" class="layui-input" style="width: 100px;display: inline">\n' +
                '                <button type="button" class="layui-btn layui-btn-sm layui-btn-danger del" style="margin-left: 15px">删除</button>\n' +
                '            </div>';
            $('.hang'+id).append(html);
        });

        //删除一行
        $(document).on('click','.del',function(){
            $(this).parent().remove();
        });

        //售价焦点价格
        $(document).on('blur','.shoujia',function(){
            var price=$(this).val();
            price=Math.floor(price * 30 * 100)/100;
            $(this).siblings('.shoujiay').html('售:'+price);
        });
        //底价焦点价格
        $(document).on('blur','.dijia',function(){
            var price=$(this).val();
            price=Math.floor(price * 30 * 100)/100;
            $(this).siblings('.dijiay').html('底:'+price);
        });

        $('.submit').click(function () {
            var sub=$(this);
            sub.attr("disabled", true);
            $.ajax({
                url:'{:url("add")}',
                data:$('#form').serialize(),
                type:'post',
                success:function (res) {
                    layer.msg('<sapn>'+res.msg+'</span>');
                    if(res.status == 'success') {
                        setTimeout(function(){
                            location.href=res.url;
                        },800)
                    }else{
                        sub.attr("disabled", false);
                    }
                }
            })
        });

        //日期
        laydate.render({
            elem: '#date'
        });

        upload.render({
            elem: '#test',
            url: "/admin/image/upload",
            done: function(res){
                $('#image').attr('src',res.path);
                $('input[name="image"]').val(res.path);
            }
        });


    })

</script>