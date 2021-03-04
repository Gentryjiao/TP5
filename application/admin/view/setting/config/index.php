<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
</head>
<body class="gray-bg" style="margin:0px auto;padding:20px">
  <form class="layui-form" id="form">
  <input type="hidden" name="id" value="{if isset($config)}{$config.id}{/if}">
  <div class="layui-form-item">
    <label class="layui-form-label">appId</label>
    <div class="layui-input-block">
      <input type="text" name="appid" lay-verify="required"  autocomplete="off" placeholder="请输入你的appId" class="layui-input" value="{if isset($config)}{$config.appid}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">secret</label>
    <div class="layui-input-block">
      <input type="text" name="secret" lay-verify="required"  autocomplete="off" placeholder="请输入你的secret" class="layui-input" value="{if isset($config)}{$config.secret}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商户号</label>
    <div class="layui-input-block">
      <input type="text" name="mch_id" lay-verify="required"  autocomplete="off" placeholder="请输入你的" class="layui-input" value="{if isset($config)}{$config.mch_id}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">支付密钥</label>
    <div class="layui-input-block">
      <input type="text" name="AppSecret" lay-verify="required"  autocomplete="off" placeholder="请输入你的支付密钥" class="layui-input" value="{if isset($config)}{$config.AppSecret}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">银行名称</label>
    <div class="layui-input-block">
      <input type="text" name="bank_name" lay-verify="required"  autocomplete="off" placeholder="请输入银行名称" class="layui-input" value="{if isset($config)}{$config.bank_name}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">银行卡号</label>
    <div class="layui-input-block">
      <input type="text" name="bank" lay-verify="required"  autocomplete="off" placeholder="请输入银行卡号" class="layui-input" value="{if isset($config)}{$config.bank}{/if}">
    </div>
  </div>

      <div class="layui-form-item" >
        <div class="layui-input-block">
          <button type="button" class="layui-btn" lay-submit lay-filter="sub" id="sub">保存</button>
        </div>
      </div>
  </form>

</body>
<script>
layui.use(['form'], function(){
var form=layui.form
  form.on('submit(sub)', function(data){
    $.post('update',data.field,function(res){
      layer.msg(res.msg)
    })
    return false;
  });
})
</script>
</html>