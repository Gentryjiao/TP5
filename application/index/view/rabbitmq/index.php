<form>
    <div class="xiaoxi"></div>
    <input type="text" class="shurukuang">
    <button type="button" class="button">发送</button>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    var ws = new WebSocket('ws://127.0.0.1:8282');
    ws.onmessage = function(e){
        var data=eval("("+e.data+")");
        console.log(data);
        $('.xiaoxi').append("<p>"+data.message+"</p>");
    }
    $('.button').click(function (){
        var inpval=$('.shurukuang').val();
        ws.send(inpval);
    })
</script>
