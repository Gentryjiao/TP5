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
                            <colgroup>
                                <col width="150">
                                <col width="700">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>介绍</th>
                                <th>详情</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>公司名称</td>
                                <td>{$data.name}</td>
                            </tr>
                            <tr>
                                <td>公司性质</td>
                                <td>{$data.nature}</td>
                            </tr>
                            <tr>
                                <td>所属行业</td>
                                <td>{$data.scale}</td>
                            </tr>
                            <tr>
                                <td>公司福利</td>
                                <td>{$data.welfare}</td>
                            </tr>
                            <tr>
                                <td>公司简介</td>
                                <td>{$data.introduction}</td>
                            </tr>
                            <tr>
                                <td>申请时间</td>
                                <td>{:date('Y-m-d H:i:s',$data.create_time)}</td>
                            </tr>
                            <tr>
                                <td>联系人</td>
                                <td>{$data.contact}</td>
                            </tr>
                            <tr>
                                <td>联系方式1</td>
                                <td>{$data.phone1}</td>
                            </tr>
                            <tr>
                                <td>联系方式2</td>
                                <td>{$data.phone2}</td>
                            </tr>
                            <tr>
                                <td>邮箱</td>
                                <td>{$data.email}</td>
                            </tr>
                            <tr>
                                <td>QQ号码</td>
                                <td>{$data.qq}</td>
                            </tr>
                            <tr>
                                <td>公司区域</td>
                                <td>{$data.area}</td>
                            </tr>
                            <tr>
                                <td>公司地址</td>
                                <td>{$data.address}</td>
                            </tr>
                            <tr>
                                <td>公司logo</td>
                                <td>
                                    <img src="{$data.logo}" style="width: 70px">
                                </td>
                            </tr>
                            <tr>
                                <td>营业执照</td>
                                <td>
                                    <img src="{$data.license}" style="width: 200px">
                                </td>
                            </tr>
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