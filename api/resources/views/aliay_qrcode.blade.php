<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>收银台</title>
    <meta name="viewport"
        content="width=device-width,initial-scale=1,shrink-to-fit=no,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Cache" content="no-cache">
    <script type="text/javascript" src="/qrcode/clipboard.min.js"></script>
    <script type="text/javascript" src="/qrcode/qrcode.min.js"></script>
    <script type="text/javascript" src="/qrcode/jquery.min.js"></script>
    <style>
        .modal {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            font-size: 16px;
        }

        button {
            padding: 5px 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .disn {
            display: none;
        }

        .payok {
            background-color: rgb(82, 169, 53);
            color: white
        }

        .payno {
            background-color: #e6f7ff;
            color: #000000
        }

        .loadingmobile {
            width: 100%;
        }

        .loader {
            margin: 100px auto;
            font-size: 25px;
            width: 1em;
            height: 1em;
            border-radius: 50%;
            position: relative;
            text-indent: -9999em;
            -webkit-animation: load5 1.1s infinite ease;
            animation: load5 1.1s infinite ease;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        @-webkit-keyframes load5 {

            0%,
            100% {
                box-shadow: 0em -2.6em 0em 0em #000000, 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.5), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.7);
            }

            12.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.7), 1.8em -1.8em 0 0em #000000, 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.5);
            }

            25% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.5), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.7), 2.5em 0em 0 0em #000000, 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            37.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.5), 2.5em 0em 0 0em rgba(0, 0, 0, 0.7), 1.75em 1.75em 0 0em #000000, 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            50% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.5), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.7), 0em 2.5em 0 0em #000000, -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            62.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.5), 0em 2.5em 0 0em rgba(0, 0, 0, 0.7), -1.8em 1.8em 0 0em #000000, -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            75% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.5), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.7), -2.6em 0em 0 0em #000000, -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            87.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.5), -2.6em 0em 0 0em rgba(0, 0, 0, 0.7), -1.8em -1.8em 0 0em #000000;
            }
        }

        @keyframes load5 {

            0%,
            100% {
                box-shadow: 0em -2.6em 0em 0em #000000, 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.5), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.7);
            }

            12.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.7), 1.8em -1.8em 0 0em #000000, 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.5);
            }

            25% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.5), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.7), 2.5em 0em 0 0em #000000, 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            37.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.5), 2.5em 0em 0 0em rgba(0, 0, 0, 0.7), 1.75em 1.75em 0 javascript:;
                0em #000000,
                0em 2.5em 0 0em rgba(0, 0, 0, 0.2),
                -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2),
                -2.6em 0em 0 0em rgba(0, 0, 0, 0.2),
                -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            50% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.5), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.7), 0em 2.5em 0 0em #000000, -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.2), -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            62.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.5), 0em 2.5em 0 0em rgba(0, 0, 0, 0.7), -1.8em 1.8em 0 0em #000000, -2.6em 0em 0 0em rgba(0, 0, 0, 0.2), -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            75% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.5), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.7), -2.6em 0em 0 0em #000000, -1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2);
            }

            87.5% {
                box-shadow: 0em -2.6em 0em 0em rgba(0, 0, 0, 0.2), 1.8em -1.8em 0 0em rgba(0, 0, 0, 0.2), 2.5em 0em 0 0em rgba(0, 0, 0, 0.2), 1.75em 1.75em 0 0em rgba(0, 0, 0, 0.2), 0em 2.5em 0 0em rgba(0, 0, 0, 0.2), -1.8em 1.8em 0 0em rgba(0, 0, 0, 0.5), -2.6em 0em 0 0em rgba(0, 0, 0, 0.7), -1.8em -1.8em 0 0em #000000;
            }
        }

        .wxtip {
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 998;
            display: none;
        }

        .wxtip-icon {
            width: 52px;
            height: 67px;
            background: url(weixin-tip.png) no-repeat;
            display: block;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .wxtip-txt {
            margin-top: 107px;
            color: #fff;
            font-size: 16px;
            line-height: 1.5;
        }

        body {
            background: #f6f6f7;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, PingFang SC, Hiragino Sans GB, Microsoft YaHei, Helvetica Neue, Helvetica, Arial, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol;
        }
    </style>
</head>

<body>
    <div id="mask" style="
            display: none;
             position:fixed;
             top: 0;
             left: 0;
             width:100%;
             height:100%;
             background:rgba(0,0,0,0.7);
             z-index: 1000;
        "></div>

    <div class="modal" id="modal" style="display: none; position: fixed; top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 300px;
                              z-index: 1001;
">
        <div style="font-size: 18px; font-weight: bold; margin: 5px auto;">请输入付款人姓名</div>
        <div style="    font-size: 14px; margin: 12px; font-weight: bold; color: red;">请正确输入付款支付宝号的真实姓名</div>
        <input type="text" style="width:275px; padding:10px" placeholder="请输入付款支付宝号姓名" id="nameInput">
        <button id="submitButton" style="width:100%; padding:10px">确定</button>
    </div>


    <div class="wxtip" id="JweixinTip">
        <span class="wxtip-icon"></span>
        <p class="wxtip-txt">点击右上角<br />选择在浏览器中打开</p>
    </div>

    <iframe id="displayFrame" noresize="noresize" style="display:none"
        sandbox="allow-same-origin allow-top-navigation allow-scripts allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-presentation"></iframe>
    <div style="max-width: 530px;margin: 0 auto;padding: 10px 20px 20px;">

       <div style="text-align: center;">
            <img style="width:150px;height:50px;  margin-bottom: 10px;" src="/qrcode/bank.png" alt="银联支付">
       </div>

       <div style=" display: block; text-align: center; ">
         <div style=" font-size: 18px;color:red;margin-bottom: 10px;text-align: center;">
            请按要求金额复制支付
        </div>
        <div style="flex:1; display: flex; justify-content:space-around; align-items: center; margin-bottom: 10px;">
            <div style="font-size: 22px; color:red;font-weight: bold; " id="amount">金额： ￥0</div>
            <div id="submitAmount"
                style="float: right;border: 1px solid #91d5ff; color: #27a5f3; background-color: #e6f7ff; border-radius: 3px; padding: 5px 10px; text-align: center;">
                复制金额
            </div>
        </div>

        <div style=" font-size: 18px;color:red;margin-bottom: 10px;text-align: center;">
            付款时请填写以下附言（备注）否则不到账
        </div>
        <div style="flex:1; display: flex; justify-content:space-around; align-items: center; margin-bottom: 10px;">
            <div style="font-size: 22px; color:red;font-weight: bold; " id="auth_code">码信息： </div>
            <div id="submitCode"
                style="float: right;border: 1px solid #91d5ff; color: #27a5f3; background-color: #e6f7ff; border-radius: 3px; padding: 5px 10px; text-align: center;">
                复制识别码
            </div>
        </div>
       </div>
        <div style=" font-size: 18px;color:red;margin-bottom: 10px;text-align: center;">
            注意：禁止使用支付宝和微信扫码付款！违者不到账！
        </div>

         <div style=" font-size: 18px;font-weight: boldd;color:blue;margin-bottom: 10px;text-align: center;">
            仅支持银行APP扫码支付
        </div>

        <div style=" font-size: 18px;font-weight: boldd;color:red;margin-bottom: 10px;text-align: center;">
            二维码一次有效禁止重复付款
        </div>

        <div style="display: flex; justify-content:space-around; align-items: center;">
            <div style="width: 20px; font-size: 18px;font-weight: boldd;color:rgb(160, 3, 3);margin-bottom: 10px;text-align: center;">
                禁止使用支付宝微信扫码付款
            </div>
            <div id="payqr" style="margin: 10px 0px 0px; min-width: 300px; min-height: 300px; background-color: #e5e5e5;"></div>
            <div style="width: 20px; font-size: 18px;font-weight: boldd;color:rgb(160, 3, 3);margin-bottom: 10px;text-align: center;">
                仅支持银行
                <span style="display: inline-block; transform: rotate(90deg); font-size: 20px; width: 20px; line-height: 20px; vertical-align: middle; margin: 0px 2px 18px 0px;">APP</span>
                扫码支付
            </div>
        </div>
        <div style="text-align: center; font-size: 16px;color: rgb(160, 3, 3);margin-bottom: 10px;" id="trade_id">
            订单号：
        </div>
        <!-- <div style="font-size: 14px;color: rgba(0,0,0,.85);margin-bottom: 10px;" id="trade_id">
            支付单号：
        </div>
        <div style="height:42px;">
            <div style="float: left;height:40px;line-height: 40px;color:rgb(119, 119, 119);" id="amount">
                充值：0元
            </div>
            <div class="payno" id="zfstatus"
                style="float: right;border: 1px solid #91d5ff;width:30%;text-align: center;height:40px;line-height: 40px;">
                -
            </div>
        </div> -->

        <div style="margin: 10px 0px 0px;border-top: 1px solid rgba(0,0,0,.06);"></div>

        <div id="payMain" class="disn">
            <div id="bg">
                <!-- <div id="payqr" style="margin: 10px 0px 0px;">
                </div> -->
                <!-- <div id="wechattext"
                    style="margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;">
                    <div style="float: left;text-align:right;width:20%;margin-top: 8px;">
                        <object data="/qrcode/qr.svg" type="image/svg+xml"></object>
                    </div>
                    <div id="alerttext" style="float: right;text-align:left;color:white;width:70%;margin-top: 7px;">
                        -
                    </div>
                </div> -->
                <div>
                    <span style="color:red;font-size: 21px;font-weight: 500;" id="timeout">请于5分00秒内支付</span>
                </div>
                <div id="paybutton" class="disn"
                    style="text-align: center;margin: 10px 0px 0px;background-color:rgb(82,169,53);color:white;height:60px;width:100%;font-size:20px;line-height:50px"
                    onclick="wechatfinsh();">点击支付</div>
            </div>
        </div>


        


        <!--<div style="color:red;font-weight: bold;padding-top: 10px;">-->
        <!--   <ul>-->
        <!--         <li>邮箱:8599521@gmail.com</li>-->
        <!--            <li>未到账等问题, 请与网站客服留言或提交工单处理!</li>-->
        <!--            <li>邮箱不处理服务内容!!!</li>-->
        <!--            <li>如果客服很长时间没有处理, 发邮箱申请投诉退款!</li>-->
        <!--            <li>切勿订单记录内投诉与反馈!!!!!</li>-->
        <!--   </ul>-->
        <!--   </div>-->

    </div>

</body>

<script type="text/javascript">
    var zf_status = "1";
    var ordertype = "1";
    var url = "";
    var qrcode_img = "";
    var isLoading = false;
    var isOver = false;
    var MyTimer;
    var ua = navigator.userAgent;
    var isWeixin = !!/MicroMessenger/i.test(ua);
    var return_url = "";


    // 设置倒计时时间为 5 分钟
    const countDownTime = 5 * 60 * 1000;
    // 获取倒计时结束时间
    var endTime = new Date().getTime() + countDownTime;
    // 每秒更新倒计时
    var countDownInterval = null;


    if (isWeixin) {
        document.getElementById('JweixinTip').style.display = 'block';
    }

    var localurl = window.location.href;

    var strs = localurl.split("/");
    var trade_id = strs[strs.length - 1];
    var amount = 100;
    var auth_code = '123'

    
    info();


    function initClipboard() {
        // 金额复制实例
        const amountClipboard = new ClipboardJS('#submitAmount', {
            text: function() {
                return amount; // 动态返回最新金额（AJAX 更新后自动生效）
            }
        });

        // 码信息复制实例
        const codeClipboard = new ClipboardJS('#submitCode', {
            text: function() {
                return auth_code; // 动态返回最新码信息
            }
        });

        // 金额复制 - 成功回调（添加用户反馈）
        amountClipboard.on('success', function(e) {
            alert(`金额 ${e.text} 已复制到剪贴板！`);
            e.clearSelection(); // 清除选中状态（优化体验）
        });

        // 金额复制 - 失败回调
        amountClipboard.on('error', function() {
            alert('金额复制失败，请手动复制！');
        });

        // 码信息复制 - 成功回调
        codeClipboard.on('success', function(e) {
            alert(`码信息 ${e.text} 已复制到剪贴板！`);
            e.clearSelection();
        });

        // 码信息复制 - 失败回调
        codeClipboard.on('error', function() {
            alert('码信息复制失败，请手动复制！');
        });
    }

    $("#trade_id").html('订单号: ' + trade_id);

    function info() {
        //alert($("#payMain").width());
        $("#paybutton").addClass("disn");
        $("#payqr").html("<div id='loading'><div class=\"loader\">Loading...</div></div>");

        $.ajax({
            type: "GET",
            url: '/trade_page/info?trade_id=' + trade_id,
            cache: false,
            dataType: "json",
            success: function (msg) {
                if (msg.code == "0") {
                    console.log(msg);
                    amount = msg.data.amount;
                    auth_code = msg.data.auth_code;
 
                    $("#amount").html('金额:      ￥' + amount);
                    $("#auth_code").html('码信息: ' + auth_code);

                    
                    initClipboard();

                    created_at = msg.data.created_at;
                    status = msg.data.status;
                    url = msg.data.url;
                    return_url = msg.data.return_url;
                    qrcode_img = msg.data.img;
                  

                    if (status == '等待支付') {
                        daojishi();

                        $("#zfstatus").html("待支付");
                        $("#zfstatus").removeClass("payok");
                        $("#zfstatus").addClass("payno");

                        // alipay
                        $("#payMain").removeClass("disn");

                        //$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");

                        if (isMobile()) {
                            $("#paybutton").removeClass("disn");
                        }

                        if (url != '') {
                            $("#payqr").html("<div id=\"qrimg\"></div>");

                            var qrcode = new QRCode("qrimg", {
                                text: url,
                                width: 300,
                                height: 300,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.L,
                            });
                        }
                        else if (qrcode_img != '') {
                            $("#payqr").html("<img src=\"" + qrcode_img + "\" width=\"300px\" height=\"300px\"></div>");
                        }
                    }
                    else if (status == '支付完成') {
                        wancheng();
                    }
                    else {
                        chaoshi();
                    }

                    checkdata();
                }
            }, error: function () {
                MyTimer = setTimeout(info, 2000);
            }
        });
    }

    function chaoshi() {
        $("#timeout").html(`支付超时`);
        $("#paybutton").addClass("disn");
        if (!isMobile()) {
            $("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width=300>");
        } else {
            $("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width='100%'>");
        }
    }

    function wancheng() {
        $("#zfstatus").html("已支付");
        $("#zfstatus").removeClass("payno");
        $("#zfstatus").addClass("payok");
        $("#paybutton").addClass("disn");

        if (!isMobile()) {
            $("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width=300>");
        } else {
            $("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width='100%'>");
        }
    }
    function daojishi() {
        countDownInterval = setInterval(() => {
            // 获取当前时间
            const now = new Date().getTime();

            // 计算倒计时剩余时间
            const timeLeft = endTime - now;

            // 将毫秒转换为分钟和秒
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // 输出倒计时剩余时间
            //console.log(`${minutes} 分钟 ${seconds} 秒`);

            // 判断是否到达倒计时结束时间
            if (timeLeft < 0) {
                chaoshi();

                // 停止倒计时
                clearInterval(countDownInterval);
                // 输出倒计时结束
                //console.log("倒计时结束");
            } else {
                $("#timeout").html(`请于${minutes}分${seconds}秒内支付`);
            }
        }, 1000);
    }


    function checkdata() {
        $.ajax({
            type: "GET",
            url: '/trade_page/query?trade_id=' + trade_id + '&t=' + new Date().getTime(),
            cache: false,
            dataType: "json",
            success: function (msg) {
                //alert(msg.code);
                if (msg.code == "0") {
                    status = msg.data.status;
                    if (status == "等待支付") {
                        MyTimer = setTimeout(checkdata, 3000);
                    } else if (status == '支付完成') {
                        clearInterval(countDownInterval);
                        isOver = true;
                        // 支付完成
                        wancheng();

                        if (return_url != "") {
                            window.setTimeout(function () { location.href = return_url }, 3000);
                        }
                    }
                }
            },
            error: function () {
                if (isLoading && isOver == false) {
                    MyTimer = setTimeout(checkdata, 3000);
                }
            }
        });
    }
    //checkdata();

    function goBack() {
        clearTimeout(MyTimer);
        clearInterval(countDownInterval);
        ordertype = "0";
        isLoading = false;
        $("#payMain").addClass("disn");
    }

    function isMobile() {
        var ua = navigator.userAgent.toLowerCase();
        _long_matches = 'googlebot-mobile|android|avantgo|blackberry|blazer|elaine|hiptop|ip(hone|od)|kindle|midp|mmp|mobile|o2|opera mini|palm( os)?|pda|plucker|pocket|psp|smartphone|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce; (iemobile|ppc)|xiino|maemo|fennec|vivo|oppo|huawei';
        _long_matches = new RegExp(_long_matches);
        _short_matches = '1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-';
        _short_matches = new RegExp(_short_matches);
        if (_long_matches.test(ua)) {
            return 1;
        }
        user_agent = ua.substring(0, 4);
        if (_short_matches.test(user_agent)) {
            return 1;
        }
        return 0;
    }

    // 移动端显示支付按钮
    if (isMobile()) {
        $("#bg").removeAttr("style");
        $("#qrcode").attr("width", "100%");
        $("#bg").attr("style", "text-align:center");

        //$("#wechattext").addClass("disn");	
        // alipay
        $("#wechattext").attr("style", "margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:100%;");
    } else {
        // alipay
       // $("#bg").attr("style", "background: url(/qrcode/alipay.png) no-repeat right center;background-size: 46%;");
        $("#wechattext").attr("style", "margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:300px;");
    }

    // alipay
    $("#alerttext").html("请使用支付宝扫描<br>二维码以完成支付");

    function wechatfinsh() {
        if (url != "") {
            //if (url.includes("https://qr.alipay.com")) {
            //$("#displayFrame").attr("src", url);//JQuery动态加载iframe。
            //
            //} else {				
            window.location = url;
            //}
        }
    }

    $("#submitButton").click(function () {
        var userInput = $("#nameInput").val();
        if (userInput.trim() !== "") {
            $.ajax({
                type: "GET",
                url: '/trade_page/payer?trade_id=' + trade_id + "&name=" + userInput,
                cache: false,
                dataType: "json",
                success: function (msg) {
                    if (msg.code == "0") {
                        $('#mask').hide();
                        $('#modal').hide();
                    } else {

                    }
                }
            });
        }
    });



   
</script>


</html>
