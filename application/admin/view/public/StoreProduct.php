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
        .layui-form-item {
            margin-bottom: 0px;
        }

        .pictrueBox {
            display: inline-block !important;
        }

        .pictrue {
            width: 60px;
            height: 60px;
            border: 1px dotted rgba(0, 0, 0, 0.1);
            margin-right: 15px;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }

        .pictrue img {
            width: 100%;
            height: 100%;
        }

        .upLoad {
            width: 58px;
            height: 58px;
            line-height: 58px;
            border: 1px dotted rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.02);
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .rulesBox {
            display: flex;
            flex-wrap: wrap;
            margin-left: 10px;
        }

        .layui-tab-content {
            margin-top: 15px;
        }

        .ml110 {
            margin: 18px 0 4px 110px;
        }

        .rules {
            display: flex;
        }

        .rules-btn-sm {
            height: 30px;
            line-height: 30px;
            font-size: 12px;
            width: 109px;
        }

        .rules-btn-sm input {
            width: 79% !important;
            height: 84% !important;
            padding: 0 10px;
        }

        .ml10 {
            margin-left: 10px !important;
        }

        .ml40 {
            margin-left: 40px !important;
        }

        .closes {
            position: absolute;
            left: 86%;
            top: -18%;
        }

        .red {
            color: red;
        }

        .layui-input-block .layui-video-box {
            width: 22%;
            height: max-content;
            border-radius: 10px;
            background-color: #707070;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .layui-input-block .layui-video-box i {
            color: #fff;
            line-height: max-content;
            margin: 0 auto;
            width: 50px;
            height: 50px;
            display: inhedit;
            font-size: 50px;
        }

        .layui-input-block .layui-video-box .mark {
            position: absolute;
            width: 100%;
            height: 30px;
            top: 0;
            background-color: rgba(0, 0, 0, .5);
            text-align: center;
        }

        .clearFix:after {
            content: '';
            display: block;
            clear: both;
        }
        #dan{
          width:87%;margin:auto
        }
        .duo{
          width:87%;margin:auto
        }
        #type{
          width:67%;height: 36px;border: 1px solid #c2c2c2;float:left;background-color: #fff
        }
        #type .deltype{
          cursor:pointer
        }
        #type span{
          line-height:36px;color:#3f3f3f;
        }
    </style>
</head>
<body class="gray-bg" style="margin:0px auto;padding:20px">
<a href="{:Url('index')}" style="margin-left:30px;"><button class="layui-btn layui-btn-sm">返回列表</button></a>
  <form class="layui-form" id="form">
  <div class="layui-form-item">
    <div class="layui-inline" style="float:left;">
      <label class="layui-form-label">分类名称</label>
      <div class="layui-input-inline"  >
        <select lay-verify="required"  lay-filter="test">
          <option value="">请选择</option>
            {volist name='select' id='vv'}
            <option value="{$vv.id}" data-cate-name="{$vv.cate_name}" {if $vv['pid']==0 }disabled{/if}>{$vv.html.$vv.cate_name}</option>
            {/volist}
        </select>
      </div>
    </div>
    <div id="type">
    {if(isset($product)) && isset($type)}
    {volist name="type" id="v"}
      <span class="deltype">&nbsp;<span class="cate_name" data-cate-id="{$v.id}">{$v.cate_name}</span>&nbsp;x</span>
    {/volist}
    {/if}
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">所属流程</label>
      <div class="layui-input-inline"  >
        <select name="flow" lay-verify="required">
          <option value="">请选择</option>
          {volist name='flow' id='v'}
            <option value="{$v.id}" {if isset($product) && $product['flow']==$v['id']}selected{/if}>{$v.flowname}</option>
          {/volist}
        </select>
      </div>
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">档次</label>
      <div class="layui-input-inline"  >
        <select name="level" lay-verify="required">
          <option value="高档" {if isset($product) && $product['level']=='高档'}selected{/if}>高档</option>
          <option value="中高档" {if isset($product) && $product['level']=='中高档'}selected{/if}>中高档</option>
          <option value="中档" {if !isset($product) || $product['level']=='中档'}selected{/if}>中档</option>
          <option value="中低档" {if isset($product) && $product['level']=='中低档'}selected{/if}>中低档</option>
          <option value="低档" {if isset($product) && $product['level']=='低档'}selected{/if}>低档</option>
         
        </select>
      </div>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">商家信息</label>
    <div class="layui-input-block">
      <input type="text" name="merchant_id" lay-verify="required"  autocomplete="off" placeholder="请输入商家信息" class="layui-input" value="{if isset($product)}{$product.merchant_id}{/if}">
    </div>
  </div>  
  <div class="layui-form-item">
    <label class="layui-form-label">产品名称</label>
    <div class="layui-input-block">
      <input type="text" name="store_name" lay-verify="required"  autocomplete="off" placeholder="请输入产品名称" class="layui-input" value="{if isset($product)}{$product.store_name}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品类型</label>
    <div class="layui-input-block">
      <input type="text" name="type" autocomplete="off" placeholder="多个用英文状态下的逗号隔开" class="layui-input"  value="{if isset($product)}{$product.type}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品品牌</label>
    <div class="layui-input-block">
      <input type="text" name="brand_name" lay-verify="required"  autocomplete="off" placeholder="请输入产品品牌" class="layui-input" value="{if isset($product)}{$product.brand_name}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品简介</label>
    <div class="layui-input-block">
      <textarea name="store_info" placeholder="请输入产品简介" class="layui-textarea">{if isset($product)}{$product.store_info}{/if}</textarea>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品关键字</label>
    <div class="layui-input-block">
      <input type="text" name="keyword" autocomplete="off" placeholder="多个用英文状态下的逗号隔开" class="layui-input"  value="{if isset($product)}{$product.keyword}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">发货地址</label>
    <div class="layui-input-block">
      <input type="text" name="fahuodizhi" autocomplete="off" placeholder="请输入发货地址" class="layui-input"  value="{if isset($product)}{$product.fahuodizhi}{/if}">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">材质</label>
    <div class="layui-input-block">
      <input type="text" name="caizhi" autocomplete="off" placeholder="请输入材质" class="layui-input"  value="{if isset($product)}{$product.caizhi}{/if}">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品主图片</label>
    <div class="layui-input-block">
      <img src="{if isset($product)}{$product.image}{/if}" alt="" class="layui-icon layui-icon-picture" id="zhutu" style="margin:7px;font-size:41px;width:41px;height:41px">    
      <input type="hidden" name="image" value="{if isset($product)}{$product.image}{/if}">
    </div>    
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">产品轮播图</label>
    <div class="layui-input-block">
    <img src="" alt="" class="layui-icon layui-icon-picture" id="lunbo" style="margin:7px;font-size:41px;width:41px;height:41px">
    {if isset($product)}
    {volist name="product.slider_image" id="v"}
      <img src="{$v}" alt="" class="layui-icon layui-icon-picture" class="imgdel" style="margin:7px;font-size:41px;width:41px;height:41px">    
      <input type="hidden" name="slider_image[]" value="{$v}">
    {/volist}
    {/if}
    </div>    
  </div>

    <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">总库存</label>
      <div class="layui-input-inline">
        <input type="text" name="stock"  autocomplete="off" class="layui-input" value="{if isset($product)}{$product.stock}{/if}">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">排序</label>
      <div class="layui-input-inline">
        <input type="text" name="sort"  autocomplete="off" class="layui-input" value="{if isset($product)}{$product.sort}{/if}">
      </div>
    </div>
<!--     <div class="layui-inline">
      <label class="layui-form-label">档次</label>
      <div class="layui-input-inline">
        <input type="text" name="level"  autocomplete="off" class="layui-input" value="{if isset($product)}{$product.level}{/if}">
      </div>
    </div> -->
    </div>
    <div class="layui-inline" style="margin:0 auto;">
        <label class="layui-form-label">产品状态</label>
        <div class="layui-input-block" style="width:max-content;margin:0px;float:left;">
        {if isset($product) && $product.is_show=='true'}
          <input type="radio" name="is_show" value="true" title="上架" checked>
          <input type="radio" name="is_show" value="false" title="下架" >
        {else\}
          <input type="radio" name="is_show" value="true" title="上架" >
          <input type="radio" name="is_show" value="false" title="下架" checked>
        {/if}
        </div>
      </div>
      <br>
    <div class="layui-inline" style="margin:0 auto;">
        <label class="layui-form-label">支持自取</label>
        <div class="layui-input-block" style="width:max-content;margin:0px;float:left;">
        {if isset($product) && $product.is_ziqu=='1'}
          <input type="radio" name="is_ziqu" value="1" title="是" checked>
          <input type="radio" name="is_ziqu" value="0" title="否" >
        {else\}
          <input type="radio" name="is_ziqu" value="1" title="是">
          <input type="radio" name="is_ziqu" value="0" title="否" checked>
        {/if}
        </div>
      </div>
      <br>
    <div class="layui-inline" style="margin:0 auto;">
        <label class="layui-form-label">支持代购</label>
        <div class="layui-input-block" style="width:max-content;margin:0px;float:left;">
        {if isset($product) && $product.is_daigou=='1'}
          <input type="radio" lay-filter="is_daigou" name="is_daigou" value="1" title="是" checked>
          <input type="radio" lay-filter="is_daigou" name="is_daigou" value="0" title="否" >
        {else\}
          <input type="radio" lay-filter="is_daigou" name="is_daigou" value="1" title="是">
          <input type="radio" lay-filter="is_daigou" name="is_daigou" value="0" title="否" checked>
        {/if}
        </div>
    </div>
    <br>
    <div class="layui-inline" id="is_buy_on" style="display:{if isset($product) && $product.is_daigou=='1'}inline-block{else\}none{/if}" >
      <label class="layui-form-label">代购方式</label>
        <div class="layui-input-block"  style="width:max-content;margin:0px;float:left;">
          <input type="checkbox" name="is_buy_on[]" value="线上代购" title="线上代购" <?php if (isset($product) && in_array('线上代购',$product['is_buy_on'])){echo 'checked';}?>>
          <input type="checkbox" name="is_buy_on[]" value="线下代购" title="线下代购" <?php if (isset($product) && in_array('线下代购',$product['is_buy_on'])){echo 'checked';}?>>
        </div>
      </div>
<br>
    <div class="layui-inline" id="duo" style="margin-bottom:20px">
      <label class="layui-form-label">选择运费</label>
      <div class="layui-input-inline">
        <select name="yunfei" lay-search="">
          <option value="">选择运费</option>
          {volist name='yunfei' id='v'}
          <option value="{$v.id}" {if isset($product) && $product['yunfei']==$v['id']}selected{/if}>{$v.name}</option>
          {/volist}
        </select>
      </div>
    </div>
    <br>
    <div class="layui-inline" id="duo" style="margin-bottom:20px">
      <label class="layui-form-label">选择规格</label>
      <div class="layui-input-inline">
        <select name="specification_name" lay-search="">
          <option value="">选择规格</option>
          {volist name='guige' id='v'}
          <option value="{$v.id}" {if isset($product) && $product['specification_name']==$v['id']}selected{/if}>{$v.specification_name}</option>
          {/volist}
        </select>
      </div>
    </div>
      <button type="button" class="layui-btn" id="create" style="margin-bottom:20px">确认</button>
      <div class="layui-fluid" id="specification_attr" style="display:none" >
          <div class="layui-row layui-col-space15" id="app" v-cloak="">
              <div class="layui-card">
                  <div class="layui-card-body clearFix">
                      <form class="layui-form" action="" id="form">
                          <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
                              <div id="guige" style="">
                                {if isset($product)}
                        {volist name='product.specification' id='v'}
                            <div class="grid-demo grid-demo-bg1">    
                                <div class="ml110">
                                    <span class="span">{$v.name}</span>
                                    <i class="layui-icon delete">ဇ</i>    
                                </div>    
                                <div class="layui-form-item rules">        
                                    <label class="layui-form-label"></label>
                                    
                                    <div class="attrs">
                                    {volist name='v.attr' id='vv'}
                                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm attr">{$vv}<i class="layui-icon layui-icon-close del"></i></button>
                                    {/volist}

                                    </div>        
                                    <div class="rules rulesBox">            
                                        <div class="rules-btn-sm">                
                                            <input type="text" name="attr" autocomplete="off" placeholder="请输入">            
                                        </div>            
                                        <button class="layui-btn layui-btn-sm add_attr" type="button">添加</button>        
                                    </div>    
                                </div>
                            </div>
                        {/volist}
                        {/if}
                              </div>
                              <div class="grid-demo grid-demo-bg1" style="margin-top: 20px">
                                  <div class="layui-form-item">
                                      <label class="layui-form-label"></label>
                                      <button class="layui-btn layui-btn-sm" type="button" id="add">添加新规格</button>
                                      <button class="layui-btn layui-btn-sm" type="button" id="request">立即生成</button>
                                  </div>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    <div class="duo" style="display:none">
      <table class="layui-table" id="test1" lay-filter="test1" ></table>
      <table class="layui-table" id="test" lay-filter="test"></table>
    </div>
<!--     <div class="duo">
    </div> -->
    </div>
  </div>
      <div class="layui-form-item" >
        <div class="layui-input-block">
          <button type="button" class="layui-btn" lay-submit="" id="sub" lay-filter="demo1">立即提交</button>
        </div>
      </div>
     </form>
</body>
<script type="text/html" id="barDemo">
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script type="text/html" id="baradd">
  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="add">添加</a>
</script>
<script>
layui.use(['form','table'], function(){
  var form = layui.form;
  var table = layui.table;
  form.render();
  //监听单元格编辑
  table.on('edit(test)', function(obj){
  });
  form.on('radio(is_daigou)', function(data){
    console.log(data.value)
    if(data.value=='1'){
      $('#is_buy_on').attr('style','display:inline-block')
    }else{
      $('#is_buy_on').attr('style','display:none')
    }
});  
});
// 选择图片
layui.use(['layer'], function(){
var $ = layui.jquery
var layer = layui.layer;
$(document).on('click','#zhutu',function(){
   var index=layer.open({
    type:2,
    title:'选择图片',
    area:['800px','500px'],
    content:'/admin/image/index?type=1',
     btn: ['确定']
     ,btn1: function(index, layero){
         var body = layer.getChildFrame('body', index);
         var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
         var companyId=iframeWin.document.getElementsByClassName("border");
         var src=$(companyId).attr('src')
         $('#zhutu').attr('src',src)
         $('input[name="image"]').val(src)
         layer.close(index);
      }
  })
})
$(document).on('click','#lunbo',function(){
   var index=layer.open({
    type:2,
    title:'选择图片',
    area:['800px','500px'],
    content:'/admin/image/index?type=2',
     btn: ['确定']
     ,btn1: function(index, layero){
         var body = layer.getChildFrame('body', index);
         var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
         var companyId=iframeWin.document.getElementsByClassName("border");
         $.each(companyId,function(index,item){
            var src=$(item).attr('src')
            $('#lunbo').parent().append('<img src="'+src+'" alt="" class="layui-icon layui-icon-picture" class="imgdel" style="margin:7px;font-size:41px;width:41px;height:41px">')
            $('#lunbo').parent().append('<input type="hidden" name="slider_image[]" value="'+src+'">')
         })
         layer.close(index);
      }
  })
})
$(document).on('click','.test1',function(){
  var this1=$(this);
  var index1=$(this).parent().parent().parent().attr('data-index')
  var tableid=$(this).parent().parent().parent().parent().parent().parent().parent().parent().attr('lay-id')
 var index=layer.open({
    type:2,
    title:'选择图片',
    area:['800px','500px'],
    content:'/admin/image/index?type=1',
     btn: ['确定']
     ,btn1: function(index, layero){
         var body = layer.getChildFrame('body', index);
         var iframeWin = window[layero.find('iframe')[0]['name']];//得到iframe页的窗口对象，执行iframe页的方法：
         var companyId=iframeWin.document.getElementsByClassName("border");
         var src=$(companyId).attr('src')
           layui.use(['form','table'], function(){
            // console.log(tableid)
            var form = layui.form;
            var table = layui.table;
            var checkStatus=table.cache[tableid];
            table.reload(tableid, {
              url: 'setimage'
              ,where: {src,checkStatus,index1} //设定异步数据接口的额外参数
            });
          });
         layer.close(index);
      }
  })
})
});
</script>
<!-- 编辑页生成的表格多规格表格 -->
{if isset($product)}

<script>
$('.duo').attr('style','display:block')
$('#specification_attr').attr('style','display:block')
  layui.use(['table'], function(){
  var table = layui.table;
  var header=[];
  header.push({field: 'id', title: 'ID'})
  header.push({field: 'image', title:'图片',width:'12.5%',templet: function(d){return '<img src="'+d.image+'" alt="" class="layui-icon layui-icon-picture test1">'}});
  $.each(<?php echo json_encode($product['specification']); ?>, function (index, obj) {
      header.push({field: obj.name, title: obj.name,width:'160px',width:'8%'});
  });
  header.push({field: 'price', title: '售价',edit:'test'});
  header.push({field: 'cost', title: '进货价',edit:'test'});
  // header.push({field: 'freight', title: '运费',edit:'test'});
  header.push({field: 'inventory', title: '库存',edit:'test'});
  header.push({field: 'subscription', title: '订金',edit:'test'});
  header.push({field: 'kg', title: '重量(KG)',edit:'test'});
  // header.push({field: 'm³', title: '体积(m³)',edit:'test'});
  // header.push({field: 'right', title:'操作', toolbar: '#barDemo'});
  table.render({
      elem: '#test'
      ,id:'test'
      ,cols: [//标题栏
        header
      ]
      ,data:<?php echo json_encode($product['product_attr']); ?>
      ,limit: <?php echo count($product['product_attr']); ?>
      ,page:false
  });
 });
</script>
{/if}

<script>
layui.use('table', function(){
  var table = layui.table;
    $('#request').click(function(){
      var specification=new Array()
      var specification_attr=new Array()
      for(i=0;i<$('.span').length;i++){
          specification[i]=$('.span:eq('+i+')').text()
          specification_attr[i]=new Array()
          for(j=0;j<$('.attrs:eq('+i+') .attr').length;j++){
              attr=$('.attrs:eq('+i+') .attr:eq('+j+')').text()
              specification_attr[i][j]=attr
          }
      }
      var data={specification:specification,specification_attr:specification_attr}
      //动态生成多规格表格
      $.post('request',data,function(data){
        if(data.status=='success'){
              var header=[];
              header.push({field: 'id', title: 'ID'})
              header.push({field: 'image', title:'图片',width:'12.5%',templet: function(d){return '<img src="'+d.image+'" alt="" class="layui-icon layui-icon-picture test1">'}});
              $.each(data.title, function (index, obj) {
                  header.push({field: obj, title: obj,width:'160px',width:'8%'});
              });
              header.push({field: 'price', title: '售价',edit:'test'});
              header.push({field: 'cost', title: '进货价',edit:'test'});
              // header.push({field: 'freight', title: '运费',edit:'test'});
              header.push({field: 'inventory', title: '库存',edit:'test'});
              header.push({field: 'subscription', title: '订金',edit:'test'});
              header.push({field: 'kg', title: '重量(KG)',edit:'test'});
              // header.push({field: 'right', title:'操作', toolbar: '#barDemo'});
            //展示已知数据
            table.render({
                elem: '#test'
                ,id:'test'
                ,cols: [//标题栏
                  header
                ]
                ,data:data.data
                ,limit: data.count//显示的数量
                ,page:false
            });
          }
      })
    $('.duo').attr('style','display:block')
    })
    //监听行工具事件
    table.on('tool(test)', function(obj){
      var data = obj.data;
      if(obj.event === 'del'){
          obj.del();
      }
    });
});
//批量添加表格
layui.use(['table'], function(){
  var table = layui.table;
  table.render({
      elem: '#test1'
      ,cols: [[//标题栏
        {field: 'image', title:'图片',width:'10%',templet: function(d){
          return '<img src="'+d.image+'" alt="" class="layui-icon layui-icon-picture test1">'
        }},        
        {field: 'price', title: '售价',edit:'test'},
        {field: 'cost', title: '进货价',edit:'test'},
        // {field: 'freight', title: '运费',edit:'test'},
        {field: 'inventory', title: '库存',edit:'test'},
        {field: 'subscription', title: '订金',edit:'test'},
        {field: 'kg', title: '重量(KG)',edit:'test'},
        {field: 'right', title:'操作', toolbar: '#baradd'},
      ]]
      ,data:[{
      "price":'0'
      ,"cost":'0'
      ,"freight":'0'
      ,"inventory":'0'
      ,"subscription":'0'
      ,"kg":'0'
      ,"m³":'0'
      ,"image":''
    }] 
      ,even: true
  });
  //监听行工具事件
  table.on('tool(test1)', function(obj){
      var checkStatus = table.cache.test;
      var checkStatus1 = table.cache.test1;
      if(obj.event === 'add'){
        table.reload('test', {
          url: 'setrequest'
          ,where: {checkStatus,checkStatus1} //设定异步数据接口的额外参数
        });
    }
  });
});

</script>
  <script>
    layui.use(['form','table'], function(){
      var form = layui.form;
      form.on('select(test)', function(data){
        var id=data.value; //得到被选中的值
        var name=$('option[value='+data.value+']').attr('data-cate-name');
        for(i=0;i<$('.cate_name').length;i++){
          var text=$('.cate_name:eq('+i+')').text()
          var textid=$('.cate_name:eq('+i+')').attr('data-cate-id')
          if(text==name && id==textid){
            layer.msg('该分类已存在')
            return false
          }
        }
        $('#type').append('<span class="deltype">&nbsp;<span class="cate_name" data-cate-id="'+id+'">'+name+'</span>&nbsp;x</span>')
      });    
      $(document).on('click','.deltype',function(){
          $(this).remove()
      })
    })
  </script>
<!-- 提交 -->
<script>
$('#sub').click(function(){
  //单规格表格
  layui.use(['table'], function(){
  var table = layui.table;
  var specification=new Array()
  for(i=0;i<$('.span').length;i++){
      specification[i] = new Array()
      var attr=[]
        for(j=0;j<$('.attrs:eq('+i+') .attr').length;j++){
          attr.push($('.attrs:eq('+i+') .attr:eq('+j+')').text())
      }
      specification[i]={
          name:$('.span:eq('+i+')').text(),
          attr:attr
      }
  }
  var slider_image=[];
  $.each($('input[name="slider_image[]"]'),function(index,item){
    slider_image.push($(item).val())
  })
  var is_buy_on=[];
  if($('input[name="is_daigou"]:checked').val()==1){
      $.each($('input[name="is_buy_on[]"]:checked'),function(index,item){
      is_buy_on.push($(item).val())
    })
  }

  var cate_id=[];
  for(i=0;i<$('.cate_name').length;i++){
    cate_id.push($('.cate_name:eq('+i+')').attr('data-cate-id'))
  }
  checkStatus = table.cache.test;
  var data={
    'cate_id':cate_id,
    'flow':$('select[name="flow"]').val(),
    'merchant_id':$('input[name="merchant_id"]').val(),
    'store_name':$('input[name="store_name"]').val(),
    'type':$('input[name="type"]').val(),
    'brand_name':$('input[name="brand_name"]').val(),
    'store_info':$('textarea[name="store_info"]').val(),
    'keyword':$('input[name="keyword"]').val(),
    'image':$('input[name="image"]').val(),
    'slider_image':slider_image,
    'sort':$('input[name="sort"]').val(),
    'stock':$('input[name="stock"]').val(),
    'level':$('input[name="level"]').val(),
    'is_show':$('input[name="is_show"]:checked').val(),
    'is_daigou':$('input[name="is_daigou"]').val(),
    'is_buy_on':is_buy_on,
    'specification_name':$('select[name="specification_name"]').val(),
    'product_attr':checkStatus,//属性详细参数
    'specification':specification,//规格
    'is_ziqu':$('input[name="is_ziqu"]:checked').val(),
    'fahuodizhi':$('input[name="fahuodizhi"]').val(),
    'caizhi':$('input[name="caizhi"]').val(),
    'yunfei':$('select[name="yunfei"]').val(),
    'level':$('select[name="level"]').val(),
  }
  {if isset($product)}
    var url='update?id={$product.id}'
  {else\}
    var url='save'
  {/if}
  $.post(url,data,function(data){
    // console.log(data)
      layer.msg(data.msg)
      if(data.status=='success'){
        setTimeout(function () {
            window.location.href = data.url;
        },1500)
      }
  })
})
});
</script>

<!--规格参数-->
<script>
$(document).on('click','.layui-form-radio',function(){
  text=$(this).find('div').text()
  if(text=='多规格'){
    $('#duo').attr('style','display:block')
  }
})

$(document).on('click','#create',function(){
  var id=$('select[name="specification_name"]').val()
  if(id==''){
    layer.msg('选择的属性无效')
    return false
  }
  $.post('getspecification_attr',{id:id},function(data){
    if(data.code==200){
      var html=''
      for(i=0;i<data.data.specification.length;i++){
        // console.log(data.data.specification_attr[i])
        // return false
        html+='<div class="grid-demo grid-demo-bg1">'
              +'<div class="ml110">'
              +'<span class="span">'+data.data.specification[i]+'</span>'
              +'<i class="layui-icon delete">ဇ</i>'
              +'</div>'
              +'<div class="layui-form-item rules">'
              +'<label class="layui-form-label"></label>'
              +'<div class="attrs">'
        for(j in data.data.specification_attr[i]){
          html+='<button type="button" class="layui-btn layui-btn-primary layui-btn-sm attr">'+data.data.specification_attr[i][j]+'<i class="layui-icon layui-icon-close del"></i></button>'
        }
        html+='</div>'
              +'<div class="rules rulesBox">'
              +'<div class="rules-btn-sm">'
              +'<input type="text" name="attr" autocomplete="off" placeholder="请输入">'
              +'</div>'
              +'<button class="layui-btn layui-btn-sm add_attr" type="button">添加</button>'
              +'</div>'
              +'</div>'
              +'</div>'
      }
      // console.log(html)
      $('#guige').html(html)
      $('#specification_attr').attr('style','display:block')
    }else{
      layer.msg(data.msg)
    }
  })
})

</script>
<!--全局layout模版-->
<script>
$('#add').click(function(){
    var html='<div class="grid-demo grid-demo-bg1 rules" style="margin-top: 24px">'
         +'                   <div class="layui-form-item layui-form-text rules">'
         +'                       <label class="layui-form-label">规格：</label>'
         +'                       <div class="rules-btn-sm">'
         +'                           <input type="text" name="guige" autocomplete="off" placeholder="请输入规格">'
         +'                       </div>'
         +'                   </div>'
         +'                   <div class="layui-form-item layui-form-text rules">'
         +'                       <label class="layui-form-label">规格值：</label>'
         +'                       <div class="rules-btn-sm">'
         +'                           <input type="text" name="guige_attr" autocomplete="off" placeholder="请输入规格值">'
         +'                       </div>'
         +'                   </div>'
         +'                   <button class="layui-btn layui-btn-sm ml40 add" type="button">添加</button>'
         +'                   <button class="layui-btn layui-btn-sm ml10 quxiao" type="button">取消</button>'
         +'               </div>'
    $('#guige').append(html)
    $(this).attr('style','display:none')
    $('#request').attr('style','display:none')
})

$(document).on('click','.add',function () {
    var guige=$('input[name="guige"]').val()
    var attr=$('input[name="guige_attr"]').val()
    console.log(guige)
    if(guige=='' || attr=='')
    {
        return false
    }
    for(i=0;i<$('.span').length;i++){
        if(guige==$('.span:eq('+i+')').text()){
            layui.use('layer', function(){
                layer=layui.layer
                layer.msg('规格重复!')
            })
            return false
        }
    }
    var obj=$(this).parent()
    $(obj).replaceWith('<div class="grid-demo grid-demo-bg1">'
                            +'    <div class="ml110"><span class="span">'+guige+'</span><i class="layui-icon delete">&#x1007;</i>'
                            +'    </div>'
                            +'    <div class="layui-form-item rules">'
                            +'        <label class="layui-form-label"></label>'
                            +'        <div class="attrs"><button type="button" class="layui-btn layui-btn-primary layui-btn-sm attr">'+attr+'<i class="layui-icon layui-icon-close del"></i></button></div>'
                            +'        <div class="rules rulesBox">'
                            +'            <div class="rules-btn-sm">'
                            +'                <input type="text" name="attr" autocomplete="off" placeholder="请输入">'
                            +'            </div>'
                            +'            <button class="layui-btn layui-btn-sm add_attr" type="button">添加</button>'
                            +'        </div>'
                            +'    </div>'
                            +'</div>')
    $('#add').attr('style','display:block;float:left')
    $('#request').attr('style','display:block;float:left')
})
$(document).on('click','.quxiao',function () {
    var obj=$(this).parent()
    $(obj).remove()
    $('#add').attr('style','display:block;float:left')
    $('#request').attr('style','display:block;float:left')
})
$(document).on('click','.add_attr',function () {
    var obj=$(this).parent().siblings()
    var attr=$(this).parent().find('input[name="attr"]').val()
    var res=new Array();
    var index=$(this).index(this)
    for(i=0;i<$('.attrs:eq('+index+') .attr').length;i++){
        if(attr==$('.attrs:eq('+index+') .attr:eq('+i+')').text()){
            layui.use('layer', function(){
                layer=layui.layer
                layer.msg('规格属性重复!')
            })
            return false
        }
    }
    if(attr==''){
        return false
    }
    $(obj[1]).append('<button type="button" class="layui-btn layui-btn-primary layui-btn-sm attr">'+attr+'<i class="layui-icon layui-icon-close del"></i></button>')
    $(this).parent().find('input[name="attr"]').val('')
    $('#add').attr('style','display:block;float:left')
    $('#request').attr('style','display:block;float:left')
})
$(document).on('click','.del',function () {
    $(this).parent().remove()
})
$(document).on('click','.delete',function () {
    $(this).parent().parent().remove()
})
</script>
</html>