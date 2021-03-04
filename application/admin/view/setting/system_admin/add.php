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
    <label class="layui-form-label">商家名称</label>
    <div class="layui-input-block">
      <input type="text" name="merchant_name" lay-verify="required"  autocomplete="off" placeholder="请输入支付宝账号" class="layui-input" value="{if isset($data)}{$data.zhifubao}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">代表人姓名</label>
    <div class="layui-input-block">
      <input type="text" name="username" lay-verify="required"  autocomplete="off" placeholder="请输入地址" class="layui-input" value="{if isset($data)}{$data.site}{/if}">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">手机号</label>
    <div class="layui-input-block">
      <input type="text" name="phone" lay-verify="required"  autocomplete="off" placeholder="请输入银行卡卡号" class="layui-input" value="{if isset($data)}{$data.bank}{/if}">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">客服姓名</label>
    <div class="layui-input-block">
      <input type="text" name="kefu" lay-verify="required"  autocomplete="off" placeholder="请输入银行卡卡号" class="layui-input" value="{if isset($data)}{$data.bank}{/if}">
    </div>
  </div>    <div class="layui-form-item">
    <label class="layui-form-label">客服电话</label>
    <div class="layui-input-block">
      <input type="text" name="kefuphone" lay-verify="required"  autocomplete="off" placeholder="请输入银行卡卡号" class="layui-input" value="{if isset($data)}{$data.bank}{/if}">
    </div>
  </div>   
  <div class="layui-form-item">
    <label class="layui-form-label">主营商品</label>
    <div class="layui-input-block">
      <input type="text" name="main" lay-verify="required"  autocomplete="off" placeholder="请输入银行卡卡号" class="layui-input" value="{if isset($data)}{$data.bank}{/if}">
    </div>
  </div>  
  <div class="layui-form-item">
    <label class="layui-form-label">营业执照</label>
    <div class="layui-input-block">
    <button type="button" class="layui-btn" id="test1">上传图片</button>
      <div class="layui-upload-list">
        <img class="layui-upload-img" id="demo1">
      </div>
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">选择地址</label>
    <div class="layui-input-block">
    <button type="button" class="layui-btn" id="site">选择</button>
      <div class="layui-upload-list">
        <p class="siteres"></p>      
        <input type="hidden" name="lonlat" value="">
      </div>
    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">详细地址</label>
    <div class="layui-input-block">
      <input type="text" name="site" lay-verify="required"  autocomplete="off" placeholder="请输入详细地址" class="layui-input" value="{if isset($data)}{$data.bank}{/if}">
    </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label"></label>
      <div class="layui-input-block">
        <button type="button" class="layui-btn" lay-submit lay-filter="sub" >保存</button>
      </div>
    </div>
    
</form>
</body>
<!-- 地图 -->
<style>
#allmap{width:100%;height:100%;}
</style>
<div id="allmap"></div>
<!-- 地图 -->
<script>
layui.use('form', function(){
  var form = layui.form;
  form.on('submit(sub)', function(){
    data={
      merchant_name:$('input[name="merchant_name"]').val(),
      username:$('input[name="username"]').val(),
      phone:$('input[name="phone"]').val(),
      kefu:$('input[name="kefu"]').val(),
      kefuphone:$('input[name="kefuphone"]').val(),
      license:$('input[name="license"]').val(),
      main:$('input[name="main"]').val(),
      pro:$('.siteres').text(),
      site:$('input[name="site"]').val(),
      lonlat:$('input[name="lonlat"]').val(),
      admin_id:'{$admin_id}',
      id:'{$id}',
    }
    $.post('{:Url('update1')}',data,function(){

    })
    // $.ajax({
    //     url:"update1",
    //     data:data,
    //     type:'post',
    //     success:function(res) {
    //       layer.msg(res.msg);
    //       if(res.code == 200) {
    //           setTimeout(function () {
    //               table.reload('test', {
    //                   where: {}
    //                 }, 'data');
    //               layer.close(index); //再执行关闭
    //           },1500)
    //       }
    //     }
    //   })
  })

});   

layui.use('upload', function(){
  var $ = layui.jquery
  ,upload = layui.upload;
    //普通图片上传
    var uploadInst = upload.render({
      elem: '#test1'
      ,url: '/admin/image/upload2' //改成您自己的上传接口
      ,done: function(res){
        layer.msg(res.msg)
        if(res.code ==200){
          $('#demo1').attr('src',res.data.url)
          $('#demo1').parent().append('<input type="hidden" name="license" value="'+res.data.url+'">')
        }
        //上传成功
      }
    });
  })
</script>
<script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=E877P8PRA2H8fVsWphlGqQTZErU7PxNY"></script>
<script type="text/javascript">
$('#site').click(function(){
  ditu=layer.open({
        type: 1,
        title: '',
        shadeClose: true,
        shade: 0.8,
        area: ['80%', '70%'],
        content: $('#allmap'), //iframe的url
        btn: ['确定'],
        yes:function () {
          layer.close(ditu)
            $('.siteres').text(siteres)
            $('input[name="lonlat"]').attr('value',pt.lng+','+pt.lat)
        },
        btn1:function () {
            layer.close(ditu)
        }
    });
})
//百度地图开始
var geolocation = new BMap.Geolocation();//获取当前位置
  // 百度地图API功能
  var map = new BMap.Map("allmap");
  map.centerAndZoom(new BMap.Point(geolocation.getCurrentPosition(function(r){
    if(this.getStatus() == BMAP_STATUS_SUCCESS){
      var mk = new BMap.Marker(r.point);
      map.addOverlay(mk);
      map.panTo(r.point);
      site=r.point.lng+', '+r.point.lat;//当前位置
    }
    else {
      layer.msg('failed'+this.getStatus());
    }        
  },{enableHighAccuracy: true})), 11);//窗口初始位置
  var geoc = new BMap.Geocoder();
  map.addEventListener('click',function(e){
    //经度  纬度
    pt= e.point;
    geoc.getLocation(pt, function(rs){
      var addComp = rs.addressComponents;
      //获取文字  位置信息
      siteres=addComp.province+addComp.city +addComp.district+addComp.street+addComp.streetNumber;
    }); 
  })

var driving = new BMap.DrivingRoute(map, { 
    renderOptions: { 
        map: map, 
        autoViewport: true 
    }
});
map.enableScrollWheelZoom(true);
//百度地图结束
</script>
</html>