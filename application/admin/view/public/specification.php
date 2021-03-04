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
            height: 180px;
            border-radius: 10px;
            background-color: #707070;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }

        .layui-input-block .layui-video-box i {
            color: #fff;
            line-height: 180px;
            margin: 0 auto;
            width: 50px;
            height: 50px;
            display: inherit;
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
    </style>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" id="app" v-cloak="">
        <div class="layui-card">
            <div class="layui-card-header">添加规格模板</div>
            <div class="layui-card-body clearFix">
                <form class="layui-form" action="" id="form">
                    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
                        <div class="layui-form-item">
                            <label class="layui-form-label">模板名称</label>
                            <div class="layui-input-block">
                                <input type="text" style="width: 80%" name="specification_name" autocomplete="off" placeholder="请输入属性规格名" class="layui-input" {if isset($c)} value="{$c.specification_name}" {/if}>
                            </div>
                        </div>
                        <!-- <div id="guige" style="">
                             
                        </div> -->
                        <div id="guige" style="">
                        {if isset($c)}
                        {volist name='c.specification' id='v'}
                            <div class="grid-demo grid-demo-bg1">    
                                <div class="ml110">
                                    <span class="span">{$v}</span>
                                    <i class="layui-icon delete">ဇ</i>    
                                </div>    
                                <div class="layui-form-item rules">        
                                    <label class="layui-form-label"></label>
                                    
                                    <div class="attrs">
                                    <?php foreach($c['specification_attr'][$key] as $vv){ ?>
                                        <button type="button" class="layui-btn layui-btn-primary layui-btn-sm attr">{$vv}<i class="layui-icon layui-icon-close del"></i></button>
                                    <?php } ?>

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
                                <button class="layui-btn layui-btn-sm" type="button" id="add">添加新规格
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
                        <div class="grid-demo grid-demo-bg1" style="margin-top: 20px;">
                            <div class="layui-form-item" style="text-align: right;">
                                <label class="layui-form-label"></label>
                                <button class="layui-btn layui-btn-normal" type="button" id="sub">保存</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--全局layout模版-->
<script>
$('#add').click(function(){
    html='<div class="grid-demo grid-demo-bg1 rules" style="margin-top: 24px">'
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
})

$(document).on('click','.add',function () {
    guige=$('input[name="guige"]').val()
    attr=$('input[name="guige_attr"]').val()
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
    obj=$(this).parent()
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
    $('#add').attr('style','display:block')
})
$(document).on('click','.quxiao',function () {
    obj=$(this).parent()
    $(obj).remove()
    $('#add').attr('style','display:block')
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
    $('#add').attr('style','display:block')
})
$(document).on('click','.del',function () {
    $(this).parent().remove()
})
$(document).on('click','.delete',function () {
    $(this).parent().parent().remove()
})

$('#sub').click(function(){
    var specification_name=$('input[name="specification_name"]').val()
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
    var id='{if isset($c)}{$c.id}{/if}'
    $.post('/admin/store.Specification/save',{specification_name:specification_name,specification:specification,specification_attr:specification_attr,id:id},function(data){
        layui.use('layer', function(){
            layer=layui.layer
            layer.msg(data.msg)
            if(data.code==200){
                setTimeout(function () {
                    parent.layui.table.reload('test');
                    var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                    parent.layer.close(index); //再执行关闭
                }, 1000);
            }
        })
        
    })
})

</script>
</body>
</html>
