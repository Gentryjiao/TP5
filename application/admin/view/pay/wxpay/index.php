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
    <label class="layui-form-label">appid</label>
    <div class="layui-input-block">
      <input type="text" name="appid" lay-verify=""  autocomplete="off" placeholder="应用ID,您的APPID。" class="layui-input" value="{if isset($wxpay)}{$wxpay.appid}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商户号</label>
    <div class="layui-input-block">
      <input type="text" name="mch_id" lay-verify=""  autocomplete="off" placeholder="" class="layui-input" value="{if isset($wxpay)}{$wxpay.mch_id}{/if}">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">body</label>
    <div class="layui-input-block">
      <input type="text" name="body" lay-verify=""  autocomplete="off" placeholder="body  " class="layui-input" value="{if isset($wxpay)}{$wxpay.body}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">通知地址</label>
    <div class="layui-input-block">
      <input type="text" name="notify_url" lay-verify=""  autocomplete="off" placeholder="通知地址" class="layui-input" value="{if isset($wxpay)}{$wxpay.notify_url}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">交易类型</label>
    <div class="layui-input-block">
      <input type="text" name="trade_type" lay-verify=""  autocomplete="off" placeholder="交易类型" class="layui-input" value="{if isset($wxpay)}{$wxpay.trade_type}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">支付秘钥</label>
    <div class="layui-input-block">
      <input type="text" name="key" lay-verify=""  autocomplete="off" placeholder="支付秘钥" class="layui-input" value="{if isset($wxpay)}{$wxpay.key}{/if}">
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
    $.post('sub',data.field,function(data){
      layer.msg(data.msg)
    })
  })
});
</script>
</html>