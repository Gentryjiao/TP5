<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
    <body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">所有分类</label>
                                <div class="layui-input-block">

                                    <select name="is_show" lay-filter="is_show">
                                        <option value="">状态</option>
                                        <option value="true">正常</option>
                                        <option value="false">冻结</option>
                                    </select>

                                </div>
                            </div>
                        <!--     <div class="layui-inline">
                                <label class="layui-form-label">所有分类</label>
                                <div class="layui-input-block">
                                    <select name="pid">
                                        <option value="">所有菜单</option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="layui-inline">
                                <label class="layui-form-label">手机号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="phone" class="layui-input" placeholder="请输入手机号">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="search" lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>搜索</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--产品列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">用户列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container">
                        <a class="layui-btn layui-btn-sm" href="{:Url('index')}">首页</a>
                    </div>
                    <table class="layui-hide" id="test" lay-filter="test"></table>
                  <!--   <script type="text/html" id="act">
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </script> -->
                </div>
            </div>
        </div>
    </div>
</div>



<script>
layui.use('table', function(){
  var table = layui.table;
  var form=layui.form;
  table.render({
    elem: '#test'
    ,url:'{:Url("getList")}'
    ,id:'test'
    ,method:'POST'
    ,limit:10//要传向后台的每页显示条数
    ,page:true
    ,cols: [[
        {field: 'id', title: 'ID',width:'5%',sort:'true'},
        {field: 'name', title: '真实姓名',width:'7%'},
        {field: 'image', title: '头像',width:'5%',templet:'#image'},
        {field: 'sex', title: '性别',width:'5%'},
        {field: 'username', title: '昵称'},
        {field: 'num', title: '用户编号'},
        {field: 'phone', title: '手机号码'},
        {field: 'create_time', title: '注册时间',sort:'true'},
        {field: 'real_status', title: '实名状态',templet:'#real_status'},
        {field: 'is_qi', title: '身份',templet:'#is_qi'},
        {field: 'is_show', title: '状态',templet:'#is_show'},
        {field: 'btn', title: '操作',width:'15%',templet:'#btn'},
    ]]
  });

    //查看详情
    $(document).on('click','.xiangqing', function(){
        var id= $(this).attr('id');
        var index=layer.open({
            type:2,
            title:'查看详情',
            area:['90%','90%'],
            content:'/admin/user.user/details?id='+id,
        })
    });

    //企业审核
    $(document).on('click','.qiye', function(){
        var id= $(this).attr('id');
        var index=layer.open({
            type:2,
            title:'信息审核',
            area:['70%','70%'],
            content:'/admin/user.user/qiye?id='+id,
            btn:['审核通过','审核拒绝'],
            btn1:function(index,layero){
                $.ajax({
                    url:"qiye_shenge",
                    data:{id:id,is_on:1},
                    type:'post',
                    success:function(res) {
                        layer.alert(res.msg);
                        if(res.status == 'success') {
                            setTimeout(function(){
                                table.reload('test', {
                                    where: {}
                                }, 'data');
                                layer.close(index); //再执行关闭
                            },1000)
                        }
                    }
                })
            },
            btn2:function(index,layero){
                $.ajax({
                    url:"qiye_shenge",
                    data:{id:id,is_on:2},
                    type:'post',
                    success:function(res) {
                        layer.alert(res.msg);
                        if(res.status == 'success') {
                            setTimeout(function(){
                                table.reload('test', {
                                    where: {}
                                }, 'data');
                                layer.close(index); //再执行关闭
                            },1000)
                        }
                    }
                })
            },
        })
    });


    //实名审核
    $(document).on('click','.shiming', function(){
        var id= $(this).attr('id');
        var index=layer.open({
            type:2,
            title:'实名认证审核',
            area:['60%','50%'],
            content:'shenhe?id='+id,
            btn:['审核通过','审核拒绝'],
            btn1:function(index,layero){
                var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
                var companyId=iframeWin.document.getElementById("form");
                $.ajax({
                    url:"shenhe_succ",
                    data:$(companyId).serialize(),
                    type:'post',
                    success:function(res) {
                        layer.alert(res.msg);
                        if(res.status == 'success') {
                            setTimeout(function(){
                                table.reload('test', {
                                    where: {}
                                }, 'data');
                                layer.close(index); //再执行关闭
                            },1000)
                        }
                    }
                })
            },
            btn2:function(index,layero){
                var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
                var companyId=iframeWin.document.getElementById("form");
                $.ajax({
                    url:"shenhe_err",
                    data:$(companyId).serialize(),
                    type:'post',
                    success:function(res) {
                        layer.alert(res.msg);
                        if(res.status == 'success') {
                            setTimeout(function(){
                                table.reload('test', {
                                    where: {}
                                }, 'data');
                                layer.close(index); //再执行关闭
                            },1000)
                        }
                    }
                })
            },
        })
    });

    //监听工具条
    table.on('tool(test)', function(obj){
        var obj=obj;
        if(obj.event === 'del'){
            layer.confirm('真的删除行么', function(index){
                $.post('{:Url("delete")}',{id:obj.data.id},function(data){
                    layer.msg(data.msg)
                    if(data.status=='success'){
                        obj.del();
                        layer.close(index);
                    }
                })
            });
        }
    });

    //显示、隐藏
    form.on('switch(is_show)', function(data){
        is_show=data.elem.checked;
        id=data.elem.id;
        $.ajax({
            url:'/admin/user.user/isshow',
            data:{is_show:is_show, id:id},
            type: 'post',
            success: function (data) {
                layer.msg(data.msg);
            }
        });
    });

    //监听下拉分类
    form.on('select(is_show)',function (data) {
        table.reload('test', {
            url: 'getList'
            ,where: {is_show:data.value}
        });
    });

    form.on('submit(search)', function(data){
      // console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
      table.reload('test', {
        url: 'getList'
        ,where: data.field
      });
      return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });
});

</script>
</body></head></html>

<!--头像-->
<script id="image" type="text/html">
    {{#
    return '<img src="'+d.image+'" alt="" width="30px" class="layui-icon layui-icon-picture test1">'
    }}
</script>

<!--账号状态-->
<script id="is_show" type="text/html">
    {{#
    if(d.is_show=='true'){
    return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="正常|冻结" checked>'
    }else{
    return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="正常|冻结">'
    }
    }}
</script>

<!--操作部分-->
<script id="btn" type="text/html">
    {{#
    var html='<a class="layui-btn layui-btn-xs xiangqing" id='+d.id+' lay-event="xiangqing">详情</a>';
    html += '<a id='+d.id+' class="layui-btn layui-btn-normal layui-btn-xs jifen" lay-event="jifen">积分</a>';
    html += '<a id='+d.id+' class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';
    return html;
    }}
</script>

<!--操作部分-->
<script id="real_status" type="text/html">
    {{#
    if(d.real_status=='0'){return '<span>未实名</span>'};
    if(d.real_status=='1'){return '<span style="color: #00a65a;font-weight: bold">已实名</span>'};
    if(d.real_status=='2'){return '<a class="layui-btn layui-btn-normal layui-btn-xs shiming" id='+d.id+' lay-event="shiming">待审核</a>'};
    if(d.real_status=='3'){return '<span style="color: #ff1c1c;font-weight: bold">审核失败</span>'};
    }}
</script>

<!--操作部分-->
<script id="is_qi" type="text/html">
    {{#
    if(d.is_qi=='0'){
        if(d.enter==0) return '<span>求职者</span>';
        if(d.enter==1) return '<a class="layui-btn layui-btn-normal layui-btn-xs qiye" id='+d.id+'>企业审核</a>';
        if(d.enter==2) return '<a class="layui-btn layui-btn-normal layui-btn-xs qiye" id='+d.id+'>个体审核</a>';
    };
    if(d.is_qi=='1'){return '<span style="color: #00a65a;font-weight: bold">企业用户</span>'};
    if(d.is_qi=='2'){return '<span style="color: #00a65a;font-weight: bold">个体户</span>'};
    }}
</script>


