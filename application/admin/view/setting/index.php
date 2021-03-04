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
                                <label class="layui-form-label">是否显示</label>
                                <div class="layui-input-block">
                                    <select name="is_show">
                                        <option value="">是否显示</option>
                                        <option value="true">显示</option>
                                        <option value="false">不显示</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit lay-filter="search">
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
                <div class="layui-card-header">分类列表</div>
                <div class="layui-card-body">
                   <!--  <div class="alert alert-info" role="alert">
                        注:名称和排序可进行快速编辑;
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div> -->
                    <div class="layui-btn-container">
                        <a class="layui-btn layui-btn-sm" href="{:Url('index')}">首页</a>
                        <button type="button" class="layui-btn layui-btn-sm" id="add">添加banner</button>
                    </div>
                    <table class="layui-hide" id="test" lay-filter="test"></table>
                    <script type="text/html" id="pic">
                        <img style="cursor: pointer" lay-event='open_image' src="{{d.pic}}">
                    </script>
                   <!--  <script type="text/html" id="is_show">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显|隐'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script> -->
                    <script type="text/html" id="act">
                        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </script>
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
    ,url:'getlist'
    ,page: true //开启分页
    ,id:'test'
    ,cols: [[
      {field:'id', title: 'ID'}
      ,{field:'title',title: '标题',edit:'test'}
      ,{field:'image',  title: '图片',templet: function(d){
            return '<img src="'+d.image+'" alt="">'
        }}
      ,{field:'url', title: '链接',edit:'test'}
      ,{field:'is_show', title: '状态',templet: function(d){
            if(d.is_show=='true'){
                return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="显|隐" checked>'
            }else{
                return '<input type="checkbox" name="is_show" id="'+d.id+'" lay-skin="switch" lay-filter="is_show" lay-text="显|隐">'
            }
        }}
        ,{field: 'sort', title: '排序',edit:'test'}
    ,{fixed: 'right', width:150,title:'操作', align:'center', toolbar: '#act'} //这里的toolbar值是模板元素的选择器
    ]]
  });
//监听工具条 
table.on('tool(test)', function(obj){  
    if(obj.event === 'del'){
        layer.confirm('真的删除行么', function(index){
            $.post('delete',{id:obj.data.id},function(data){
                layer.msg(data.msg)
                if(data.code==200){
                    table.reload('test', {
                      where: {}
                    }, 'data');
                    layer.close(index);
                }
            })
            
        });
    }
    if(obj.event === 'edit'){
        var index=layer.open({
        type:2,
        title:'编辑banner',
        area:['60%','60%'],
        content:'edit?id='+obj.data.id,
        btn:['提交'],
        yes:function(index,layero){
            var body = layer.getChildFrame('body', index);
             var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
             var companyId=iframeWin.document.getElementById("form");
             $.ajax({
              url:"update1?id="+obj.data.id,
              data:$(companyId).serialize(),
              type:'post',
              success:function(res) {
                layer.msg(res.msg);
                if(res.code == 200) {
                    setTimeout(function () {
                        table.reload('test', {
                            where: {}
                          }, 'data');
                        layer.close(index); //再执行关闭
                    },1500)
                }
              }
            })
        }
    })
    }
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
    $.post('update',data,function(data){
        layer.msg(data.msg)
        if(data.code!=200){
            table.reload('test', {
            where: {}
          }, 'data');
        }
    })
});
form.on('switch(is_show)', function(data){
  is_show=data.elem.checked
  id=data.elem.id
  data={
    field:'is_show',
    value:is_show,
    id:id
  }
  $.post('update',data,function(data){
    layer.msg(data.msg)
        if(data.code!=200){
            table.reload('test', {
            where: {}
          }, 'data');
        }
  })

});    

$(document).on('click','#add',function(){
    var index=layer.open({
        type:2,
        title:'添加banner',
        area:['60%','60%'],
        content:'create',
        btn:['提交'],
        yes:function(index,layero){
            var body = layer.getChildFrame('body', index);
             var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
             var companyId=iframeWin.document.getElementById("form");
             $.ajax({
              url:"save",
              data:$(companyId).serialize(),
              type:'post',
              success:function(res) {
                layer.msg(res.msg);
                if(res.code == 200) {
                    setTimeout(function () {
                        table.reload('test', {
                            where: {}
                          }, 'data');
                        layer.close(index); //再执行关闭
                    },1500)
                }
              }
            })
        }
    })
})
form.on('submit(search)', function(data){
  // console.log(data.field)
  // return false
  table.reload('test', {
    url: 'getlist'
    ,where: data.field
  });
  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
});
});
</script>