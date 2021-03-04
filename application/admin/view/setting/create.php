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
    <label class="layui-form-label">标题</label>
    <div class="layui-input-block">
      <input type="text" name="title" autocomplete="off" placeholder="请输入标题" class="layui-input" value="{if isset($data)}{$data.title}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">链接</label>
    <div class="layui-input-block">
      <input type="text" name="url" autocomplete="off" placeholder="请输入链接" class="layui-input" value="{if isset($data)}{$data.url}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">选择图片</label>
    <div class="layui-input-block">
      <img src="{if isset($data)}{$data.image}{/if}" alt="" class="layui-icon layui-icon-picture" id="zhutu" style="margin:7px;font-size:41px;width:41px;height:41px">
      <input type="hidden" name="image" value="{if isset($data)}{$data.image}{/if}">
    </div>
  </div>
  <div class="layui-inline">
    <label class="layui-form-label">是否显示</label>
      <div class="layui-input-block"  style="width:max-content;margin:0px;float:left;">
      {if isset($data) && $data['is_show']=='false'}
        <input type="radio" name="is_show" value="true" title="显示">
        <input type="radio" name="is_show" value="false" title="隐藏" checked>
      {else\}
        <input type="radio" name="is_show" value="true" title="显示" checked>
        <input type="radio" name="is_show" value="false" title="隐藏">
      {/if}
      </div>
  </div>
</form>
</body>
<script>
layui.use('form', function(){
  var form = layui.form;
  
  $('#zhutu').click(function(){
    var index=layer.open({
      type:2,
      title:'选择图片',
      area:['800px','400px'],
      content:'/admin/image/index',
       btn: ['确定']
       ,btn1: function(index, layero){
           var body = layer.getChildFrame('body', index);
           var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
           var companyId=iframeWin.document.getElementById("LAY_demo3");
           src=$(companyId).find('.on').attr('src')
           $('#zhutu').attr('src',src)
           $('input[name="image"]').val(src)
           layer.close(index);
        }
    })
  })
});
</script>
</html>