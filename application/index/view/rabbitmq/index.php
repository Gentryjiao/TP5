<style>
    form{
        width:250px;
    }
    .xiaoxi p.left{
        text-align: left;
    }
    .xiaoxi p.right{
        text-align: right;
    }
</style>
<form>
    <div class="xiaoxi"></div>
    <input type="text" class="shurukuang">
    <button type="button" class="button">发送</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var ws = new WebSocket('ws://127.0.0.1:8282');
    ws.onmessage = function(e){
        // json数据转换成js对象
        var data = eval("("+e.data+")");
        console.log(data);
        var type = data.type || '';
        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init': //绑定id
                var from_user_id='{$from_user_id}';
                var init_data='{"type":"init","from_user_id":"'+from_user_id+'"}';
                ws.send(init_data);
                break;
            case 'messages': //发送消息
                $('.xiaoxi').append("<p class='left'>"+data.messages+"</p>")
                break;
            case 'logout': //离线
                console.log(data);
                break;
            case 'save': //操作数据库
                dbSave(data)
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            default :
                alert(e.data);
        }
    }
    //发送消息
    $('.button').click(function (){
        var inpval=$('.shurukuang').val()
        $('.xiaoxi').append("<p class='right'>"+inpval+"</p>")
        var to_user_id='{$to_user_id}';
        console.log('to_user_id',to_user_id);
        var messages_data='{"type":"messages","to_user_id":"'+to_user_id+'","messages":"'+inpval+'"}';
        ws.send(messages_data);
    })
    function dbSave(data){
        $.ajax({
            url:'/user',
            type:'post',
            data:data,
            success:function(res){
                console.log(res);
            }
        })
    }
</script>
