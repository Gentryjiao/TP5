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
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
      <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="index">全部图片</a>
      </li>
        {volist name="type" id="v"}
        {if $v['pid']=='0'}
        <li class="layui-nav-item">
          <a class="" href="javascript:;">{$v.name}</a>
          <dl class="layui-nav-child">
            {volist name='type' id='vv'}
            {if $v['id']==$vv['pid']}
            <dd><a href="index?type={$vv.id}">{$vv['name']}</a></dd>
            {/if}
            {/volist}
          </dl>
        </li>
        {/if}
        {/volist}
      </ul>
    </div>
  </div>
  <div class="layui-body">

      <div class="site-demo-flow" id="LAY_demo3">
      {volist name="data.data" id='v'}
        <img src="{$v.image}" class="">
      {/volist}
      </div>  
  </div>
  </body>
<script>
layui.use('upload', function(){
var $ = layui.jquery
,upload = layui.upload;
//多图片上传
// upload.render({
//   elem: '#test2'
//   ,url: '/admin/image/upload?pid=' //改成您自己的上传接口
//   ,multiple: true
//   ,done: function(res){
//     layer.msg(res.msg)
//     if(res.code==200){
//       setTimeout(function () {
//         window.location.reload();
//       },1500)
//     }
//   }
// });


$('#add_type').click(function(){
  layer.open({
    type:2,
    title:'选择图片',
    area:['500px','300px'],
    content:'/admin/widget.images/addcate/id/0?id=0',
  })
})

//多图片
{if $imgtype==2}
  $('#LAY_demo3 img').click(function(){
    var attr=$(this).attr('class')
    if(attr!='border'){
      $(this).attr('class','border')
    }else{
      $(this).attr('class','')
    }
  })
{else\}
//单图片
  $('#LAY_demo3 img').click(function(){
    $('#LAY_demo3 img').attr('class','')
    $(this).attr('class','border')
  })
{/if}


});





</script>
<script>
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</html>