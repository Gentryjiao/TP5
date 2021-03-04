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
      <input type="text" name="app_id" lay-verify=""  autocomplete="off" placeholder="应用ID,您的APPID。" class="layui-input" value="{if isset($alipay)}{$alipay.app_id}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商户私钥</label>
    <div class="layui-input-block">
      <textarea name="merchant_private_key" id="" class="layui-textarea" cols="10" rows="5" placeholder="商户私钥，您的原始格式RSA私钥">{if isset($alipay)}{$alipay.merchant_private_key}{/if}</textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">异步通知地址</label>
    <div class="layui-input-block">
      <input type="text" name="notify_url" lay-verify=""  autocomplete="off" placeholder="异步通知地址" class="layui-input" value="{if isset($alipay)}{$alipay.notify_url}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">同步跳转</label>
    <div class="layui-input-block">
      <input type="text" name="return_url" lay-verify=""  autocomplete="off" placeholder="同步跳转" class="layui-input" value="{if isset($alipay)}{$alipay.return_url}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">编码格式</label>
    <div class="layui-input-block">
      <input type="text" name="charset" lay-verify=""  autocomplete="off" placeholder="编码格式" class="layui-input" value="{if isset($alipay)}{$alipay.charset}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">签名方式</label>
    <div class="layui-input-block">
      <input type="text" name="sign_type" lay-verify=""  autocomplete="off" placeholder="签名方式" class="layui-input" value="{if isset($alipay)}{$alipay.sign_type}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">支付宝网关</label>
    <div class="layui-input-block">
      <input type="text" name="gatewayUrl" lay-verify=""  autocomplete="off" placeholder="支付宝网关" class="layui-input" value="{if isset($alipay)}{$alipay.gatewayUrl}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">支付宝公钥</label>
    <div class="layui-input-block">
      <textarea name="alipay_public_key" id="" class="layui-textarea" cols="10" rows="5" placeholder="商户私钥，您的原始格式RSA私钥">{if isset($alipay)}{$alipay.alipay_public_key}{/if}</textarea>
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