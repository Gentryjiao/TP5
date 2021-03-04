<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
</head>
<style>
  #LAY_demo3 img{
    width:110px;height:100px;margin:10px;
  }
  .border{
    width:110px;height:100px;margin:7px;border: 3px solid red;
  }
</style>
<body class="layui-layout-body">
   <form class="layui-form" id="form">
  <div class="layui-form-item">
    <div class="layui-inline" style="float:left;">
      <label class="layui-form-label">分类名称</label>
      <div class="layui-input-inline"  >
        <select lay-verify="required" name="pid" lay-filter="test">
          <option value="0">顶级分类</option>
            {volist name='type' id='vv'}
            <option value="{$vv.id}">{$vv.name}</option>
            {/volist}
        </select>
      </div>
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">分类名称</label>
    <div class="layui-input-block">
      <input type="text" name="name" lay-verify="required"  autocomplete="off" placeholder="请输入分类名称" class="layui-input" value="">
    </div>
  </div>

  </form>
</body>
<script>
layui.use('form', function(){


});





</script>

</html>