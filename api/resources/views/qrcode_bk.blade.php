<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>收银台</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Cache" content="no-cache">
    <script type="text/javascript" src="/qrcode/qrcode.min.js"></script>
    <script type="text/javascript" src="/qrcode/jquery.min.js"></script>
	<style>
		.disn{ display:none;}
		.payok{ background-color: rgb(82, 169, 53);color:white}
		.payno{ background-color: #e6f7ff;color:#000000}
		
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
			box-shadow: 0em -2.6em 0em 0em #000000, 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.5), -1.8em -1.8em 0 0em rgba(0,0,0, 0.7);
		  }
		  12.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.7), 1.8em -1.8em 0 0em #000000, 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.5);
		  }
		  25% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.5), 1.8em -1.8em 0 0em rgba(0,0,0, 0.7), 2.5em 0em 0 0em #000000, 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  37.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.5), 2.5em 0em 0 0em rgba(0,0,0, 0.7), 1.75em 1.75em 0 0em #000000, 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  50% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.5), 1.75em 1.75em 0 0em rgba(0,0,0, 0.7), 0em 2.5em 0 0em #000000, -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  62.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.5), 0em 2.5em 0 0em rgba(0,0,0, 0.7), -1.8em 1.8em 0 0em #000000, -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  75% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.5), -1.8em 1.8em 0 0em rgba(0,0,0, 0.7), -2.6em 0em 0 0em #000000, -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  87.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.5), -2.6em 0em 0 0em rgba(0,0,0, 0.7), -1.8em -1.8em 0 0em #000000;
		  }
		}
		@keyframes load5 {
		  0%,
		  100% {
			box-shadow: 0em -2.6em 0em 0em #000000, 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.5), -1.8em -1.8em 0 0em rgba(0,0,0, 0.7);
		  }
		  12.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.7), 1.8em -1.8em 0 0em #000000, 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.5);
		  }
		  25% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.5), 1.8em -1.8em 0 0em rgba(0,0,0, 0.7), 2.5em 0em 0 0em #000000, 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  37.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.5), 2.5em 0em 0 0em rgba(0,0,0, 0.7), 1.75em 1.75em 0 javascript:;0em #000000, 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  50% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.5), 1.75em 1.75em 0 0em rgba(0,0,0, 0.7), 0em 2.5em 0 0em #000000, -1.8em 1.8em 0 0em rgba(0,0,0, 0.2), -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  62.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.5), 0em 2.5em 0 0em rgba(0,0,0, 0.7), -1.8em 1.8em 0 0em #000000, -2.6em 0em 0 0em rgba(0,0,0, 0.2), -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  75% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.5), -1.8em 1.8em 0 0em rgba(0,0,0, 0.7), -2.6em 0em 0 0em #000000, -1.8em -1.8em 0 0em rgba(0,0,0, 0.2);
		  }
		  87.5% {
			box-shadow: 0em -2.6em 0em 0em rgba(0,0,0, 0.2), 1.8em -1.8em 0 0em rgba(0,0,0, 0.2), 2.5em 0em 0 0em rgba(0,0,0, 0.2), 1.75em 1.75em 0 0em rgba(0,0,0, 0.2), 0em 2.5em 0 0em rgba(0,0,0, 0.2), -1.8em 1.8em 0 0em rgba(0,0,0, 0.5), -2.6em 0em 0 0em rgba(0,0,0, 0.7), -1.8em -1.8em 0 0em #000000;
		  }
		}

		.wxtip{background: rgba(0,0,0,0.8); text-align: center; position: fixed; left:0; top: 0; width: 100%; height: 100%; z-index: 998; display: none;}
		.wxtip-icon{width: 52px; height: 67px; background: url(weixin-tip.png) no-repeat; display: block; position: absolute; right: 20px; top: 20px;}
		.wxtip-txt{margin-top: 107px; color: #fff; font-size: 16px; line-height: 1.5;}

        body{
            background: #f6f6f7;font-family: -apple-system,BlinkMacSystemFont,Segoe UI,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol;
        }


	</style>
</head>
<body>

<div class="wxtip" id="JweixinTip">
<span class="wxtip-icon"></span>
<p class="wxtip-txt">点击右上角<br/>选择在浏览器中打开</p>
</div>

<iframe id="displayFrame" noresize="noresize" style="display:none" sandbox= "allow-same-origin allow-top-navigation allow-scripts allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-popups-to-escape-sandbox allow-presentation"></iframe>
<div onclick="goBack();" id="payback" class="disn" style="cursor: pointer; position: absolute; top: 20px; left: 20px; background: #626365; color: #fff; padding: 4px 20px 4px 10px; font-size: 14px; border-radius: 100px;">
< 返回
</div>
<div style="max-width: 530px;margin: 0 auto;padding: 10px 20px 20px;">

	<div style="font-size: 21px;font-weight: 500;color: #000;margin-bottom: 15px;text-align: center;">
		收银台
	</div>

	<div style="font-size: 14px;color: rgba(0,0,0,.85);margin-bottom: 15px;">
		支付单号：C77CD106
	</div>
	<div style="height:42px;">
		<div style="float: left;height:40px;line-height: 40px;color:rgb(119, 119, 119);">
			充值：20.6元
		</div>
		<div class="payno" id="zfstatus"style="float: right;border: 1px solid #91d5ff;width:30%;text-align: center;height:40px;line-height: 40px;">
			-
		</div>
	</div>


	<div style="margin: 10px 0px 0px;border-top: 1px solid rgba(0,0,0,.06);"></div>
	
	
	<div id="payMain" class="disn">
	
		<div id="bg">
			<div id="payqr" style="margin: 10px 0px 0px;">
				
			</div>
			
			<div id="wechattext" style="margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;">
				<div style="float: left;text-align:right;width:20%;margin-top: 8px;">
					<object data="/qrcode/qr.svg" type="image/svg+xml"></object>
				</div>
				
				<div id="alerttext" style="float: right;text-align:left;color:white;width:70%;margin-top: 7px;">
					-
				</div>				
			</div>
			
			<div>
			    <span style="color:red;font-size: 21px;font-weight: 500;" id="timeout">请于5分00秒内支付</span>
			</div>
			<div id="paybutton" class="disn" style="text-align: center;margin: 10px 0px 0px;background-color:rgb(82,169,53);color:white;height:60px;width:100%;font-size:20px;line-height:50px" onclick="wechatfinsh();">点击支付</div>
		</div>
	</div>
	
	
	<div id="payment">
		<div style="margin-top: 10px;color: #777; font-size: 14px; font-weight: 300;	text-align: center;">
		请从以下选项中选择一个支付方式
		</div>
		
		<div onclick='getPay(2)' id="wx" style="margin-top: 10px;cursor: pointer; transition: all .3s; display: none; border-radius: 10px; background: #fff; margin-bottom: 1.25rem;">
			<div style="padding: 20px; border-right: 1px solid #f7f7f7;	">
	
				<div style="width: 45px; height: 45px; border-radius: 100%;"><img style="width: 100%;" src="data:image/png;base64,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"></div>
			
			</div>
			

			<div style="padding: 20px; flex: 4 1; font-size: 1.125rem; font-weight: 500; color: rgba(48,49,51,.85);	">
				微信
				<div style="font-weight: 300;margin-top: 5px; color: #aaa; font-size: 12px;">推荐 更快更安全</div>
			</div>


		</div>
		
		<div onclick='getPay(1)' id="zfb" style="margin-top: 10px;cursor: pointer; transition: all .3s; display: flex; border-radius: 10px; background: #fff; margin-bottom: 1.25rem;">
			<div style="padding: 20px; border-right: 1px solid #f7f7f7;	">
	
				<div style="width: 45px; height: 45px; border-radius: 100%;"><img style="width: 100%;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNTg1MjM1Njc2NjE4IiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjEyMzMiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PGRlZnM+PHN0eWxlIHR5cGU9InRleHQvY3NzIj48L3N0eWxlPjwvZGVmcz48cGF0aCBkPSJNNDE3LjcyMDMyIDE3Ni44MDA0MjdsLTIuMDIwNjkzIDY2LjAyMDY5M0wxNDkuNTkyNzQ3IDI0Mi44MjExMmwwIDUwLjUyNDE2IDI2OS40NzU4NCAwIDAgODcuNTc5MzA3TDE5Ni43NTEzNiAzODAuOTI0NTg3bDAgNDcuODM0NDUzIDIyNS42ODI3NzMtMi4wMjQxMDcgMjI2LjM1ODYxMy0yLjAyMDY5My00LjA0MTM4NyAxNi4xNjg5NmMtMTAuMTA2ODggMzkuMDc1ODQtNTMuMjI0MTA3IDE0My40OTY1MzMtNjEuMzA2ODggMTQ2Ljg2MjA4LTQuNzE3MjI3IDEuMzQ4MjY3LTM3LjcyNzU3My02LjA2MjA4LTc0Ljc3OTMwNy0xNi44NDQ4LTExMi41MDY4OC0zNC4zNTUyLTE2OC40MjQxMDctNDQuNDYyMDgtMjQ0LjU0ODI2Ny00NC40NjIwOC03Ny40NzU4NCAwLjY3MjQyNy0xMjggMTQuODI0MTA3LTE3Ni41MDY4OCA0OS4xNzkzMDctOTQuMzEzODEzIDY3LjM2ODk2LTEwMS43MjQxNiAxOTkuNDEzNzYtMTQuMTQ0ODUzIDI3Mi44NDQ4IDkyLjk2NTU0NyA3OC4xNDgyNjcgMjQ1LjIyMDY5MyA4NS41NTUyIDM4My4zMjQxNiAxOC4xODk2NTMgNDkuMTc5MzA3LTIzLjU3OTMwNyAxMzcuNDMxMDQtODcuNTc5MzA3IDE2Ny4wNzI0MjctMTIwLjU4OTY1M2wxMi4xMjc1NzMtMTQuMTUxNjggMzguNCAxOC44NjU0OTNjMzguNCAxOS41MzQ1MDcgMjc1LjUzNzkyIDEyOS4zNDgyNjcgMzE1Ljk1ODYxMyAxNDYuMTg5NjUzbDIxLjU1NTIgOS40MzEwNCAwLTE3My4xMzc5Mi01OC42MTAzNDctMTUuNDkzMTJjLTUzLjIyMDY5My0xNC4xNTE2OC0xODkuMzA2ODgtNTYuNTg5NjUzLTIzMy4wOTY1MzMtNzMuNDM3ODY3bC0xNi44NDQ4LTYuNzM0NTA3IDIyLjIzNDQ1My00NS44MTAzNDdjMjMuNTc5MzA3LTQ3LjE1NTIgNzAuNzM3OTItMTgwLjU0ODI2NyA3MC43Mzc5Mi0yMDAuMDg2MTg3IDAtOS40MzEwNC0xMy40NzU4NC0xMC43NzkzMDctMTE3Ljg5NjUzMy0xMC43NzkzMDdsLTExNy44OTMxMiAwTDU2MC41MzQxODcgMjkzLjM0NTI4bDI4Mi45NTE2OCAwTDg0My40ODU4NjcgMjQ4Ljg4MzJjMC0yLjAyMDY5My02NC0zLjM2ODk2LTE0MS40NzU4NC0yLjY5MzEybC0xNDEuNDcyNDI3IDAuNjcyNDI3TDU2MC41Mzc2IDExMS40NTIxNmwtMTQwLjggMEw0MTcuNzIwMzIgMTc2LjgwMDQyN3pNMzkxLjQ0NDQ4IDYxNi4wMzg0YzM1LjAzNDQ1MyAxMi4xMzA5ODcgODEuNTE3MjI3IDMwLjk5MzA2NyAxMDMuMDc1ODQgNDIuNDQ0OGwzOS4wNzI0MjcgMjAuODg2MTg3LTIxLjU1ODYxMyAyNi45NDQ4NTNjLTEyLjEyNDE2IDE0LjgyMDY5My0zOS43NDQ4NTMgNDAuNDIwNjkzLTYyLjY1MTczMyA1Ny4yNjU0OTMtNzYuOCA1Ny4yNTg2NjctMTQ4LjIxMDM0NyA3OS40OTMxMi0yMzkuODMxMDQgNzQuMTAzNDY3LTk1LjY2MjA4LTUuMzg5NjUzLTE0Ni44NjIwOC00NC40NjIwOC0xNTIuOTI3NTczLTExNy4yMjA2OTMtNC4wNDEzODctNDUuMTM3OTIgNi4wNjU0OTMtNzAuNzM3OTIgMzcuMDU1MTQ3LTk0LjMxNzIyNyAzOS4wNzI0MjctMjkuNjQ0OCA3MC43MzQ1MDctMzYuMzc1ODkzIDE1Ni45NjU1NDctMzQuMzU1MkMzMTYuNjY4NTg3IDU5My44MTA3NzMgMzM3LjU1MTM2IDU5Ny44NTIxNiAzOTEuNDQ0NDggNjE2LjAzODRMMzkxLjQ0NDQ4IDYxNi4wMzg0eiIgcC1pZD0iMTIzNCIgZmlsbD0iIzQ1OUVFMyI+PC9wYXRoPjwvc3ZnPg=="></div>
			
			</div>
			

			<div style="padding: 20px; flex: 4 1; font-size: 1.125rem; font-weight: 500; color: rgba(48,49,51,.85);	">
				支付宝
				<div style="font-weight: 300;margin-top: 5px; color: #aaa; font-size: 12px;">生活好 支付宝</div>
			</div>
            

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
	var url = "https://openapi.alipay.com/gateway.do?method=alipay.trade.page.pay&amp;app_id=2021004108669983&amp;charset=utf-8&amp;version=1.0&amp;sign_type=RSA2&amp;timestamp=2024-04-13%2002%3A40%3A52&amp;notify_url=https%3A%2F%2Falicallback-c.dgsfweb.com%2Falinotify&amp;return_url=https%3A%2F%2Fcheckout.dgsfweb.com%2Freturn&amp;sign=F95jBPpLbVWY5evDdPgQ6IUFdd3muJ0YsQAbKbF0pcUy51iyOWTKt3MGo%2Fyd5BhSp%2Fc5n%2FzyoV23nrX%2BCSFUoFmXdJalHRL9taAMR5wGgxuOjudKV46suWRz8cQaWfpeBiIRL5dLQAUo%2B3d54WQK7NmdaJyJFyzjYItBHUx1%2FlgLKLYnn8G9BNkmoK48abQtQOv7jvBku61TTEFF8t9j8%2BIFPHy5l%2B3M0d06RKB9u9btjcwglkWf1dV%2BNA11G1P15GKYcRepdyn6VlV2PwkpFLWKDwOoi6vLfDyIJI%2FREJKbw8ntnMNhg2C%2FCRg1w%2B7%2Fb8h8U4TxrhDeDs1i6DTFRw%3D%3D&amp;alipay_sdk=alipay-sdk-nodejs-3.2.0&amp;biz_content=%7B%22out_trade_no%22%3A%22171294724076013973648QR%22%2C%22qr_pay_mode%22%3A%224%22%2C%22qrcode_width%22%3A%22300%22%2C%22timeout_express%22%3A%2210m%22%2C%22product_code%22%3A%22FAST_INSTANT_TRADE_PAY%22%2C%22total_amount%22%3A20.6%2C%22subject%22%3A%22%E7%A1%95%E9%A3%9E%E5%95%86%E5%9F%8E%20-%20%E5%95%86%E5%93%81%22%7D";
	var isLoading = false;
	var isOver = false;
	var MyTimer;
	var ua = navigator.userAgent;
	var isWeixin =  !!/MicroMessenger/i.test(ua);
	var backurl = "https://www.dgsfweb.com/home/order/orderpay/171294724076013973648";

	if(isWeixin){
		document.getElementById('JweixinTip').style.display='block';
	}
	
	function choose() {
		//alert($("#payMain").width());
		$("#paybutton").addClass("disn");
		var owidth = "";
		if(isMobile()) {
			owidth = $("#payMain").width();
		} else {
			owidth = "300";
		}
		
		$.ajax({
			type:"GET",
			url:'/orders/choose/171294724076013973648/' + ordertype + "/" + owidth,
			cache:false,
			dataType:"json",
			success:function(msg){
				if(msg.code == "0") {				
					checkdata();
				}
			},error: function(){
				MyTimer = setTimeout(choose,1000);
			}
		});
	}
    
	function checkdata() {
	    var fsn = "1"
	    if (window.localStorage) {
    	    fsn = window.localStorage.getItem("fsn");
        	if(fsn == undefined) {
        	    window.localStorage.setItem("fsn", "171294724076013973648");
        	}
	    }
    	
		$.ajax({
			type:"GET",
			url:'/orders/status/171294724076013973648/' + ordertype + '/' + fsn,
			cache:false,
			dataType:"json",
			success:function(msg){
				//alert(msg.code);
				if(msg.code == "0") {
					orderStatus = msg.data.orderStatus;
					url = msg.data.url;
					//$("#bg").attr("style","text-align:center");
					if(orderStatus == "0") {
						if(ordertype == "1" && url != "") {
							if(isLoading == false) {
								isLoading = true;
								
								
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
                                          $("#timeout").html(`支付超时`);
                                          $("#paybutton").addClass("disn");
                                          if(!isMobile()) {
                                    			$("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width=300>");
                                    	  }else {
                                    			$("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width='100%'>");
                                    	  }
                                          
                                        // 停止倒计时
                                        clearInterval(countDownInterval);
                                        // 输出倒计时结束
                                        //console.log("倒计时结束");
                                      }else {
                                            $("#timeout").html(`请于${minutes}分${seconds}秒内支付`);
                                      }
                                    }, 1000);
								
								
								if(isMobile()) {
									$("#paybutton").removeClass("disn");
									if (url.includes("https://qr.alipay.com")) {
									  $("#payqr").html("<div id=\"qrimg\"></div>");
								
										var qrcode = new QRCode("qrimg", {
											text: url,
											width: 300,
											height: 300,
											colorDark : "#000000",
											colorLight : "#ffffff",
											correctLevel : QRCode.CorrectLevel.L,
										});										

									} else {
									  $("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=" + $("#paybutton").width() + " height=" + $("#paybutton").width() + "></iframe>");
									}
									
									
								}else {
									if (url.includes("https://qr.alipay.com")) {
									  $("#payqr").html("<div id=\"qrimg\"></div>");
								
										var qrcode = new QRCode("qrimg", {
											text: url,
											width: 300,
											height: 300,
											colorDark : "#000000",
											colorLight : "#ffffff",
											correctLevel : QRCode.CorrectLevel.L,
										});	

									} else {
										$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");									
									}
								}
								
							}
						}else if(ordertype == "2" && url != "") {
							if(isLoading == false) {
								isLoading = true;
								//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
								$("#payqr").html("<div id=\"qrimg\"></div>");
								
								var qrcode = new QRCode("qrimg", {
									text: url,
									width: 300,
									height: 300,
									colorDark : "#000000",
									colorLight : "#ffffff",
									correctLevel : QRCode.CorrectLevel.L,
								});	
							}
						}else if(ordertype == "3" && url != "") {
							if(isLoading == false) {
								isLoading = true;
								//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
								
								$("#paybutton").removeClass("disn");
								
								$("#payqr").html("<div id=\"qrimg\"></div>");
								var qrcode = new QRCode("qrimg", {
									text: window.location.href,
									width: $("#paybutton").width(),
									height: $("#paybutton").width(),
									colorDark : "#000000",
									colorLight : "#ffffff",
									correctLevel : QRCode.CorrectLevel.L,
								});	
							//	wechatfinsh();
								
							}
						}else if(ordertype == "4" && url != "") {
							if(isLoading == false) {
								isLoading = true;
								//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
								
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
                                          $("#timeout").html(`支付超时`);
                                          $("#paybutton").addClass("disn");
                                          if(!isMobile()) {
                                    			$("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width=300>");
                                    	  }else {
                                    			$("#payqr").html("<img src=/qrcode/qrcode_timeout.png border=0 width='100%'>");
                                    	  }
                                          
                                        // 停止倒计时
                                        clearInterval(countDownInterval);
                                        // 输出倒计时结束
                                        //console.log("倒计时结束");
                                      }else {
                                            $("#timeout").html(`请于${minutes}分${seconds}秒内支付`);
                                      }
                                    }, 1000);
								
								
								$("#paybutton").removeClass("disn");
								
								$("#payqr").html("<div id=\"qrimg\"></div>");
								var qrcode = new QRCode("qrimg", {
									text: window.location.href,
									width: $("#paybutton").width(),
									height: $("#paybutton").width(),
									colorDark : "#000000",
									colorLight : "#ffffff",
									correctLevel : QRCode.CorrectLevel.L,
								});	
							//	wechatfinsh();
								
							}
						}
					}else {
					    clearInterval(countDownInterval);
						isOver = true;
						// 支付完成
						$("#payback").addClass("disn");
						$("#zfstatus").html("已支付");	
						$("#zfstatus").removeClass("payno");		
						$("#zfstatus").addClass("payok");	
						$("#paybutton").addClass("disn");
						
						if(!isMobile()) {
							$("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width=300>");
						}else {
							$("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width='100%'>");
						}
						
						if(msg.data.backurl != ""){
						    window.setTimeout(function(){location.href=msg.data.backurl},1000);
						}
					}
				}else {
					//qrcode_timeout();
					//clearInterval(myTimer);
				}
				if(((isLoading && isOver == false) || url == "") && ordertype != 0) {
					MyTimer = setTimeout(checkdata,1000);
				}
			},
			error: function(){
				if(isLoading && isOver == false) {
					MyTimer = setTimeout(checkdata,1000);
				}
			}
		});
	}
	//checkdata();
	
	
    	// 设置倒计时时间为 5 分钟
    const countDownTime = 5 * 60 * 1000;
    // 获取倒计时结束时间
    const endTime = new Date().getTime() + countDownTime;
    // 每秒更新倒计时
    var countDownInterval = null;
	
	function goBack() {
		clearTimeout(MyTimer);
		clearInterval(countDownInterval);
		ordertype = "0";
		isLoading = false;
		$("#payback").addClass("disn");
		$("#payment").removeClass("disn");	
		$("#payMain").addClass("disn");
	}
	
	
	
	
	function getPay(ptype) {
	
		if(isMobile()) {
			if(ptype == 2) {
				ptype = 3;
			}else if(ptype == 1) {
				ptype = 4;
			}
		}
	
		// show payment
		$("#payback").removeClass("disn");
		$("#payment").addClass("disn");	
		$("#payMain").removeClass("disn");
		// loading 
		//$("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width=300>");
		$("#payqr").html("<div id='loading'><div class=\"loader\">Loading...</div></div>");
		if(isMobile()) {
			$("#loading").addClass("loadingmobile");
			
			if(ptype == 1) {
				// alipay
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:100%;");
			}else if(ptype == 2) {
				// wechat
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:100%;");
			}else if(ptype == 3) {
				// wechat
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:100%;");
			}
			
		}else {
			$("#loading").attr("style","border: 1px solid rgba(255, 255, 255, 0);width:300px;height:300px");
			
			if(ptype == 1) {
				// alipay
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:300px;");
			}else if(ptype == 2) {
				// wechat
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;");
			}else if(ptype == 3) {
				// wechat
				$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;");
			}
		}
	
		if(ptype == 1) {
			// alipay
			ordertype = "1";
			if(isMobile()) {
				$("#bg").removeAttr("style");
			}else {
				$("#bg").attr("style","background: url(/qrcode/alipay.png) no-repeat right center;background-size: 46%;");	
			}
			$("#alerttext").html("请使用支付宝扫描<br>二维码以完成支付");
		}else if(ptype == 2) {
			// wechat
			ordertype = "2";
			if(isMobile()) {
				$("#bg").removeAttr("style");
			}else {
				$("#bg").attr("style","background: url(/qrcode/wechat.png) no-repeat right center;background-size: 46%;");	
			}
			$("#alerttext").html("请使用微信扫描<br>二维码以完成支付");
		}else if(ptype == 3) {
			// wechat
			ordertype = "3";
			if(isMobile()) {
				$("#bg").removeAttr("style");
			}else {
				$("#bg").attr("style","background: url(/qrcode/wechat.png) no-repeat right center;background-size: 46%;");	
			}
			$("#alerttext").html("请使用微信扫描<br>二维码以完成支付");
		}else if(ptype == 4) {
			// wechat
			ordertype = "4";
		    if(isMobile()) {
				$("#bg").removeAttr("style");
			}else {
				$("#bg").attr("style","background: url(/qrcode/alipay.png) no-repeat right center;background-size: 46%;");	
			}
			$("#alerttext").html("请使用支付宝扫描<br>二维码以完成支付");
		}
		
		choose();
		//checkdata();
		//setTimeout(function(){alert("Hello")},3000);
		
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
	
	
	if(zf_status == "0") {
		$("#zfstatus").html("待支付");		
		$("#zfstatus").removeClass("payok");	
		$("#zfstatus").addClass("payno");
		
		if(ordertype == "1") {
			// alipay
			$("#payment").addClass("disn");	
			$("#payMain").removeClass("disn");
			
			//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
			$("#payback").removeClass("disn");
			MyTimer = setTimeout(checkdata,1000);
		} else if(ordertype == "2") {
			// wechat
			$("#payment").addClass("disn");	
			$("#payMain").removeClass("disn");
			
			
			$("#payqr").html("<div id=\"qrimg\"></div>");
									
			var qrcode = new QRCode("qrimg", {
				text: url,
				width: 300,
				height: 300,
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.L,
			});	
			
			//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
			$("#payback").removeClass("disn");
			MyTimer = setTimeout(checkdata,1000);
		}  else if(ordertype == "3") {
			// wechat H5
			$("#payment").addClass("disn");	
			$("#payMain").removeClass("disn");
			
			
			$("#payqr").html("<div id=\"qrimg\"></div>");
			var qrcode = new QRCode("qrimg", {
				text: window.location.href,
				width: $("#payMain").width(),
				height: $("#payMain").width(),
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.L,
			});	
			
			//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
			$("#payback").removeClass("disn");
			MyTimer = setTimeout(checkdata,1000);
			//wechatfinsh();
		} else if(ordertype == "4") {
			// wechat H5
			$("#payment").addClass("disn");	
			$("#payMain").removeClass("disn");
			
			
			$("#payqr").html("<div id=\"qrimg\"></div>");
			var qrcode = new QRCode("qrimg", {
				text: window.location.href,
				width: $("#payMain").width(),
				height: $("#payMain").width(),
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.L,
			});	
			
			//$("#payqr").html("<iframe scrolling='no' frameborder='no' src='" + url + "' width=300 height=300></iframe>");
			$("#payback").removeClass("disn");
			MyTimer = setTimeout(checkdata,1000);
			//wechatfinsh();
		}
		
	}else {
		$("#zfstatus").html("已支付");	
		$("#zfstatus").removeClass("payno");		
		$("#zfstatus").addClass("payok");		
		
		$("#payment").addClass("disn");	
		$("#payMain").removeClass("disn");
		if(!isMobile()) {
			$("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width=300>");
		}else {
			$("#payqr").html("<img src=/qrcode/pay_ok.png border=0 width='100%'>");
		}
		if(backurl != ""){
	    	window.setTimeout(function(){location.href=backurl},1000);
		}
	}
	
	// 移动端显示支付按钮
	if(isMobile()) {
		$("#bg").removeAttr("style");
		$("#qrcode").attr("width","100%");
		$("#bg").attr("style","text-align:center");
		
		$("#wechattext").addClass("disn");	

		if(ordertype == 1) {
			// alipay
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:100%;");
		}else if(ordertype == 2) {
			// wechat
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:100%;");
		}else if(ordertype == 3) {
			// wechat
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:100%;");
		}else if(ordertype == 4) {
			// wechat
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:100%;");
		}
	}else {	
		if(ordertype == 1) {
			// alipay
			$("#bg").attr("style","background: url(/qrcode/alipay.png) no-repeat right center;background-size: 46%;");	
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:300px;");
		}else if(ordertype == 2) {
			// wechat
			$("#bg").attr("style","background: url(/qrcode/wechat.png) no-repeat right center;background-size: 46%;");
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;");
		}else if(ordertype == 3) {
			// wechat
			$("#bg").attr("style","background: url(/qrcode/wechat.png) no-repeat right center;background-size: 46%;");
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:rgb(82,169,53);height:60px;width:300px;");
		}else if(ordertype == 4) {
			// alipay
			$("#bg").attr("style","background: url(/qrcode/alipay.png) no-repeat right center;background-size: 46%;");	
			$("#wechattext").attr("style","margin: 3px 0px 0px;background-color:#108ee9;height:60px;width:300px;");
		}
	}
	
	if(ordertype == 1) {
		// alipay
		$("#alerttext").html("请使用支付宝扫描<br>二维码以完成支付");
	}else if(ordertype == 2) {
		// wechat
		$("#alerttext").html("请使用微信扫描<br>二维码以完成支付");
	}else if(ordertype == 3) {
		// wechat
		$("#alerttext").html("请使用微信扫描<br>二维码以完成支付");
	}else if(ordertype == 4) {
		// wechat
		$("#alerttext").html("请使用支付宝扫描<br>二维码以完成支付");
	}

	function wechatfinsh() {
		if(url != "") {	

			if (url.includes("https://qr.alipay.com")) {
				$("#displayFrame").attr("src", url);//JQuery动态加载iframe。
				
			} else {				
		    		window.location = url;
			}

		
		//	$("#displayFrame").attr("src", url);//JQuery动态加载iframe。
		}
	}
	


</script>


</html>
