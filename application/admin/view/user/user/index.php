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
                                    <select name="is_show">
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
        {field: 'btn', title: '操作',width:"16%",templet:function(d){
            var html=''
            if(d.is_on==0){
                html +='<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="jihuo">激活</a>'
            }
            return html
        }},
        {field: 'id', title: 'ID'},
        {field: 'zsname', title: '真实姓名'},
        {field: 'nickname', title: '昵称'},
        {field: 'phone', title: '用户名'},
        {field: 'yve', title: '余额'},
        {field: 'baozhengjin', title: '保证金',edit:'test'},
        {field: 'code', title: '邀请码'},
        {field: 'pcode', title: '注册码'},
        {field: 'sex', title: '性别'},
        {field: 'user_jifen', title: '个人积分'},
        {field: 'group_jifen', title: '团队积分'},
        {field: 'lv', title: '等级',edit:'test'},
        {field: 'image', title: '头像',templet:function(d){
            return '<img src="'+d.image+'" alt="" class="layui-icon layui-icon-picture test1">'
        }},
        {field: 'is_show', title: '状态',templet: function(d){
            if(d.is_show=='true'){
                return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="正常|冻结" checked>'
            }else{
                return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="正常|冻结">'
            }
        }},
        
    ]]
  });
//监听工具条 
table.on('tool(test)', function(obj){  
    var obj=obj
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
    if(obj.event === 'jihuo'){
        layer.confirm('确定要激活吗?', function(index){
            $.post('{:Url("jihuo")}',{id:obj.data.id},function(data){
                layer.msg(data.msg)
                if(data.status=='success'){
                    layer.close(index);
                    table.reload('test', {
                        where: {}
                      }, 'data');
                }
            })
        });
    }
});

form.on('switch(is_show)', function(data){
  is_show=data.elem.checked
  id=data.elem.id
  data={
    field:'is_show',
    value:is_show,
    id:id
  }
  $.post('{:Url('update1')}',data,function(data){
    layer.msg(data.msg)
    if(data.status!='success'){
        table.reload('test', {
        where: {}
      }, 'data');
    }
  })

});
//监听单元格编辑
table.on('edit(test)', function(obj){
    var field=obj.field
    var value=obj.value
    var id=obj.data.id
    data={
        field:field,
        value:value,
        id:id
    }
    $.post('{:Url('update1')}',data,function(data){
        layer.msg(data.msg)
        if(data.status!='success'){
            table.reload('test', {
            where: {}
          }, 'data');
        }
    })
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