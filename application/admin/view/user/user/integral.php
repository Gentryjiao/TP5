<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <link href="{__PLUG_PATH}layui/css/layui.css" rel="stylesheet">
    <script src="{__PLUG_PATH}layui/layui.js"></script>
    <style>
        .layui-form-item{
            margin-top:20px;width: 100%
        }
    </style>
</head>
<body class="gray-bg" style="margin:0px auto;padding:0px auto">
<form class="layui-form" id="form">

    <div style="padding:10px;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">

                        <table class="layui-table">
<!--                            <colgroup>-->
<!--                                <col width="150">-->
<!--                                <col width="700">-->
<!--                            </colgroup>-->
                            <thead>
                            <tr>
                                <th>状态</th>
                                <th>时间</th>
                                <th>交易量</th>
                                <th>剩余</th>
                                <th>说明</th>
                            </tr>
                            </thead>

                            <tbody>

                            {volist name='data' id='d'}
                            <tr>
                                <td>
                                    {if $d.status==1}
                                        <span style="color: #00a65a;font-weight: bold">+收入<span>
                                    {else/}
                                        <span style="color: #ff1c1c;font-weight: bold">-消耗<span>
                                    {/if}
                                </td>
                                <td>{:date('Y-m-d H:i:s',$d.create_time)}</td>
                                <td>{$d.tran}</td>
                                <td>{$d.surplus}</td>
                                <td>{$d.descrip}</td>
                            </tr>
                            {/volist}

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</body>

</html>