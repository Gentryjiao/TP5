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
   <!--      <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <div class="layui-form-item">
                            <div class="layui-inline">
                                <label class="layui-form-label">产品名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="specification_name" class="layui-input" placeholder="请输入分类名称">
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
        </div> -->
        <!--产品列表-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">分类列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container">
                        <a class="layui-btn layui-btn-sm" href="{:Url('imglist')}">首页</a>
                        <button type="button" class="layui-btn layui-btn-sm" id="add">添加分类</button>
                        <button type="button" class="layui-btn layui-btn-sm" id="addimg">添加图片</button>
                    </div>
                    <table class="layui-hide" id="test" lay-filter="test"></table>
                    <script type="text/html" id="pic">
                        <img style="cursor: pointer" lay-event='open_image' src="{{d.pic}}">
                    </script>
                   <!--  <script type="text/html" id="is_show">
                        <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显|隐'  {{ d.is_show == 1 ? 'checked' : '' }}>
                    </script> -->
                    <script type="text/html" id="act">
                        <a class="layui-btn layui-btn-xs" lay-event="check">查看子级</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </script>
                    <script type="text/html" id="act1">
                        <!-- <a class="layui-btn layui-btn-xs" lay-event="add_img" id="test5">添加图片</a> -->
                        <a class="layui-btn layui-btn-xs" lay-event="imagelist">查看图片</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                    </script>
                    <script type="text/html" id="act2">
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delimg">删除</a>
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
    ,id:'test'    
    ,method:'POST'
    ,limit:20
    ,page:true
    ,cols: [[
        {field: 'id', title: 'ID',width:'10%'},
        {field: 'name', title: '分类名称',edit:'test'},
        {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'14%'},
    ]]
  });



$('#add').click(function(){
    var index=layer.open({
        type:2,
        title:'添加分类',
        area:['60%','60%'],
        content:'add_type',
        btn:['提交'],
        yes:function(index,layero){
             var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
             var companyId=iframeWin.document.getElementById("form");
             $.ajax({
              url:"{:Url('save_type')}",
              data:$(companyId).serialize(),
              type:'post',
              success:function(res) {
                layer.msg(res.msg);
                if(res.status == 'success') {
                    layer.close(index);
                    setTimeout(function () {
                        table.reload('test', {
                            where: {}
                          }, 'data');
                    },1500)
                }
              }
            })
        }
    })
})

$('#addimg').click(function(){
    var index=layer.open({
        type:2,
        title:'添加图片',
        area:['60%','60%'],
        content:'add_img',
        btn:['提交'],
        yes:function(index,layero){
             var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
             var companyId=iframeWin.document.getElementById("form");
             console.log($(companyId).serialize())
             // return ;
             $.ajax({
              url:"{:Url('save_img')}",
              data:$(companyId).serialize(),
              type:'post',
              success:function(res) {
                layer.msg(res.msg);
                if(res.status == 'success') {
                    layer.close(index);
                    setTimeout(function () {
                        table.reload('test', {
                            where: {}
                          }, 'data');
                    },1500)
                }
              }
            })
        }
    })
})


//监听工具条 
table.on('tool(test)', function(obj){  
    var obj=obj
    if(obj.event === 'del'){
        layer.confirm('真的删除行么', function(index){
            $.post('delete',{id:obj.data.id},function(data){
                layer.msg(data.msg)
                if(data.status=='success'){
                    obj.del()
                }
            })
            
        });
    }
    if(obj.event === 'edit'){
        var index=layer.open({
            type:2,
            title:'编辑规格',
            area:['60%','60%'],
            content:'edit?id='+obj.data.id,
        })
    }

    if(obj.event === 'check'){
          table.render({
            elem: '#test'
            ,url:'getlist1?id='+obj.data.id
            ,id:'test'    
            ,method:'POST'
            ,limit:20
            ,page:true
            ,cols: [[
                {field: 'id', title: 'ID',width:'10%'},
                {field: 'name', title: '分类名称',edit:'test'},
                {field: 'right', title: '操作',align:'center',toolbar:'#act1',width:'14%'},
            ]]
          });
    }
    if(obj.event === 'imagelist'){
          table.render({
            elem: '#test'
            ,url:'getlist2?id='+obj.data.id
            ,id:'test2'    
            ,method:'POST'
            ,limit:20
            ,page:true
            ,cols: [[
                {field: 'id', title: 'ID',width:'10%'},
                {field: 'image', title: '图片',templet:function(d){
                    return '<img src="'+d.image+'" alt="" class="layui-icon layui-icon-picture test1">'
                }},
                {field: 'right', title: '操作',align:'center',toolbar:'#act2',width:'14%'},
            ]]
          });
    }
    if(obj.event === 'delimg'){
        $.post('deleteimg',{id:obj.data.id},function(data){
            layer.msg(data.msg)
            if(data.status=='success'){
                obj.del()
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
    $.post('update1',data,function(data){
        layer.msg(data.msg)
        if(data.status!='success'){
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
  $.post('update1',data,function(data){
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
    url: 'Specification_List'
    ,where: data.field
  });
  return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
});
});
</script>
