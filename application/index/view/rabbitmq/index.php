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
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<!--<script>-->
<!--    var ws = new WebSocket('ws://127.0.0.1:8282');-->
<!--    ws.onmessage = function(e){-->
<!--        // json数据转换成js对象-->
<!--        var data = eval("("+e.data+")");-->
<!--        console.log(data);-->
<!--        var type = data.type || '';-->
<!--        switch(type){-->
<!--            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定-->
<!--            case 'init': //绑定id-->
<!--                var from_user_id='{$from_user_id}';-->
<!--                var init_data='{"type":"init","from_user_id":"'+from_user_id+'"}';-->
<!--                ws.send(init_data);-->
<!--                break;-->
<!--            case 'messages': //发送消息-->
<!--                $('.xiaoxi').append("<p class='left'>"+data.messages+"</p>")-->
<!--                break;-->
<!--            case 'logout': //离线-->
<!--                console.log(data);-->
<!--                break;-->
<!--            case 'save': //操作数据库-->
<!--                dbSave(data)-->
<!--                break;-->
<!--            // 当mvc框架调用GatewayClient发消息时直接alert出来-->
<!--            default :-->
<!--                alert(e.data);-->
<!--        }-->
<!--    }-->
<!--    //发送消息-->
<!--    $('.button').click(function (){-->
<!--        var inpval=$('.shurukuang').val()-->
<!--        $('.xiaoxi').append("<p class='right'>"+inpval+"</p>")-->
<!--        var to_user_id='{$to_user_id}';-->
<!--        console.log('to_user_id',to_user_id);-->
<!--        var messages_data='{"type":"messages","to_user_id":"'+to_user_id+'","messages":"'+inpval+'"}';-->
<!--        ws.send(messages_data);-->
<!--    })-->
<!--    function dbSave(data){-->
<!--        $.ajax({-->
<!--            url:'/user',-->
<!--            type:'post',-->
<!--            data:data,-->
<!--            success:function(res){-->
<!--                console.log(res);-->
<!--            }-->
<!--        })-->
<!--    }-->
<!--</script>-->

<script type="text/javascript">
    var lockReconnect = false;//避免重复连接
    var wsUrl = "ws://127.0.0.1:8282";
    var ws;
    var tt;
    createWebSocket();

    //发送消息
    $('.button').click(function (){
        var inpval=$('.shurukuang').val()
        $('.xiaoxi').append("<p class='right'>"+inpval+"</p>")
        var to_user_id='{$to_user_id}';
        console.log('to_user_id',to_user_id);
        var messages_data='{"type":"messages","to_user_id":"'+to_user_id+'","messages":"'+inpval+'"}';
        ws.send(messages_data);
    })

    function createWebSocket() {
        try {
            ws = new WebSocket(wsUrl);
            init();
        } catch(e) {
            console.log('catch');
            reconnect(wsUrl);
        }
    }
    function init() {
        ws.onclose = function () {
            console.log('链接关闭');
            reconnect(wsUrl);
        };
        ws.onerror = function() {
            console.log('发生异常了');
            reconnect(wsUrl);
        };
        ws.onopen = function () {
            //心跳检测重置
            heartCheck.start();
        };
        ws.onmessage = function (event) {
            //拿到任何消息都说明当前连接是正常的
            console.log('接收到消息');
            // json数据转换成js对象
            var data = eval("("+event.data+")");
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
            heartCheck.start();
        }
    }
    function reconnect(url) {
        if(lockReconnect) {
            return;
        }
        lockReconnect = true;
        //没连接上会一直重连，设置延迟避免请求过多
        tt && clearTimeout(tt);
        tt = setTimeout(function () {
            createWebSocket(url);
            lockReconnect = false;
        }, 4000);
    }
    //心跳检测
    var heartCheck = {
        timeout: 3000,
        timeoutObj: null,
        serverTimeoutObj: null,
        start: function(){
            console.log('start');
            var self = this;
            this.timeoutObj && clearTimeout(this.timeoutObj);
            this.serverTimeoutObj && clearTimeout(this.serverTimeoutObj);
            this.timeoutObj = setTimeout(function(){
                //这里发送一个心跳，后端收到后，返回一个心跳消息，
                console.log('55555');
                ws.send("123456789");
                self.serverTimeoutObj = setTimeout(function() {
                    console.log(111);
                    console.log(ws);
                    ws.close();
                    // createWebSocket();
                }, self.timeout);

            }, this.timeout)
        }
    }
    // createWebSocket(wsUrl);
</script>
