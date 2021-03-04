<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
    </script>
    <style>
        .layui-form-item{
          margin-top:20px;width: 90%
        }
    </style>
</head>
<body class="gray-bg" style="margin:0px auto;padding:0px auto">
  <form class="layui-form" id="form">
  <div class="layui-form-item">
    <label class="layui-form-label">accessKeyId</label>
    <div class="layui-input-block">
      <input type="text" name="accessKeyId" lay-verify=""  autocomplete="off" placeholder="" class="layui-input" value="{if isset($alisms)}{$alisms.accessKeyId}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">accessKeySecret</label>
    <div class="layui-input-block">
      <textarea name="accessKeySecret" id="" class="layui-textarea" cols="10" rows="5" placeholder="">{if isset($alisms)}{$alisms.accessKeySecret}{/if}</textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">签名名称</label>
    <div class="layui-input-block">
      <input type="text" name="sign" lay-verify=""  autocomplete="off" placeholder="" class="layui-input" value="{if isset($alisms)}{$alisms.sign}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">模板code</label>
    <div class="layui-input-block">
      <input type="text" name="code" lay-verify=""  autocomplete="off" placeholder="" class="layui-input" value="{if isset($alisms)}{$alisms.code}{/if}">
    </div>
  </div>

  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="button" class="layui-btn" lay-submit="" lay-filter="sub">保存</button>
    </div>
  </div>

</form>
</body>
<script>
layui.use('form', function(){
  var form = layui.form;

  form.on('submit(sub)',function(data){
    $.post('sub1',data.field,function(data){
      layer.msg(data.msg)
    })
  })
});
</script>
</html>