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
        <select lay-verify="required" name="type" lay-filter="test">
          <option value="">请选择</option>
            {volist name="data" id='v'}
              {if $v['pid']==0}
              <optgroup label="{$v.name}">
                {volist name="data" id='vv'}
                {if $vv['pid']==$v['id']}
                <option value="{$vv.id}">{$vv.name}</option>
                {/if}
                {/volist}
              </optgroup>
              {/if}
            {/volist}
     
        </select>
      </div>
    </div>
  </div>

    <div class="layui-form-item">
      <div class="layui-inline" style="float:left;">
        <label class="layui-form-label">选择图片</label>
            <button type="button" class="layui-btn" id="test2">上传图片</button>
                <div class="layui-upload-list" id="demo2">
                </div>
      </div>
    </div>
  </form>
</body>

<script>

layui.use(['upload','form'], function(){
var $ = layui.jquery
,upload = layui.upload;
var form=layui.form
var url=''
//图片上传
// upload.render({
//   elem: '#test1'
//   ,url: 'upload' //改成您自己的上传接口
//   // multiple: true
//   ,done: function(res){
//     layer.msg(res.msg)
//     if(res.status=='success'){
//         $('input[name="image"]').val(res.path)
//         $('#img').attr('src',res.path)
//     }
//   }
// });
  //多图片上传
  upload.render({
    elem: '#test2'
    ,url: 'upload' //改成您自己的上传接口
    ,multiple: true
    // ,before: function(obj){
    //   //预读本地文件示例，不支持ie8
    //   obj.preview(function(index, file, result){
    //     $('#demo2').append('<img src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
    //   });
    // }
    ,done: function(res){
        var html='<div style="float:left" class="del">'
                  +'<img src="'+ res.path +'" class="layui-upload-img" style="width:120px;height:120px">'
                  +'<input type="hidden" name="image[]" value="'+res.path+'">'
                  +'</div>'
        $('#demo2').append(html)
        // $('#demo2').append('<input type="hidden" name="image[]" value="'+res.path+'">')
    }
  });
$(document).on('click','.del',function(){
  $(this).remove()
})

})



</script>

</html>