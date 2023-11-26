<?php
include("api.inc.php");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<title>购买授权</title>
<script src="Public/static/jquery.min.js"></script>
<script src="Public/static/layer.js"></script>
<link href="Public/static/icon.css" rel="stylesheet">  
<link rel="stylesheet" href="Public/static/material.min.css">  
<link href="Public/static/wenquan.css" rel="stylesheet"> 
<script src="Public/static/md5.js"></script>
<script src="Public/static/material.min.js"></script>
</head>  
					<div class="mdl-tabs__panel is-active" id="tab-panel1" style=" margin-left: auto;margin-right: auto;z-index:1;max-width:1153px;top:100px;width:92%">
					<div class="buy-card-wide mdl-card mdl-shadow--2dp" style="margin : 20px 0px 0px 0px;padding-bottom: 2ex;">
						<div class="mdl-card__title">
							<h2 class="mdl-card__subtitle-text"><font color="#FFFFFF" size="3">在线购买授权</font></h2>
						</div>
						<form class="form-horizontal" action="./other/ptsubmit.php" method="post" target="_blank">
						<div style="width: 90%;margin:0 auto;">
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="out_trade_no" value="<?php echo $confs['ptmoney']?>" readonly = "readonly">
								<label class="mdl-textfield__label" for="need">商品单价</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="out_trade_no" value="<?php echo date("YmdHis").rand(100000,999999); ?>" readonly = "readonly">
								<label class="mdl-textfield__label" for="kc">订单号</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="url">
								<label class="mdl-textfield__label" for="kc">需要授权的域名</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="qq">
								<label class="mdl-textfield__label" for="kc">需要授权的QQ</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="user">
								<label class="mdl-textfield__label" for="kc">登录账号</label>
							</div>
							<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
								<input class="mdl-textfield__input" type="text" name="pass">
								<label class="mdl-textfield__label" for="kc">登录密码</label>
							</div>
							<br>

							<div style="width: 100%;">
								<div class="mdl-list__item" style="display:inline;display:-moz-inline-box; display:inline-block;" >
									<span class="mdl-list__item-secondary-action" style="display:inline;margin : 1px 1px 1px 1px;display:-moz-inline-box; display:inline-block;">选择合适您的支付方式：</span>
									<span class="mdl-list__item-secondary-action" style="display:inline;margin : 1px 1px 1px 1px;display:-moz-inline-box; display:inline-block;">
										<label class="demo-list-radio mdl-radio mdl-js-radio mdl-js-ripple-effect" for="alipay">
										<input type="radio" id="alipay" class="mdl-radio__button" name="type" value="alipay" title="支付宝" checked>
											<span class="mdl-checkbox__label"><img src="./static/alipay.ico">&nbsp;支付宝&nbsp;&nbsp;</span>
										</label>
									</span>
									<span class="mdl-list__item-secondary-action" style="display:inline;margin : 1px 1px 1px 1px;display:-moz-inline-box; display:inline-block;">
										<label class="demo-list-radio mdl-radio mdl-js-radio mdl-js-ripple-effect" for="wxpay">
											<input type="radio" id="wxpay" class="mdl-radio__button" name="type" value="wxpay" title="微信钱包">
											<span class="mdl-checkbox__label"><img src="./static/wechat.ico">&nbsp;微信钱包</span>
										</label>
									</span>
									<span class="mdl-list__item-secondary-action" style="display:inline;margin : 1px 1px 1px 1px;display:-moz-inline-box; display:inline-block;">
										<label class="demo-list-radio mdl-radio mdl-js-radio mdl-js-ripple-effect" for="qqpay">
											<input type="radio" id="qqpay" class="mdl-radio__button" name="type" value="qqpay" title="QQ钱包">
											<span class="mdl-checkbox__label"><img src="./static/qqpay.ico">&nbsp;QQ钱包&nbsp;&nbsp;</span>
										</label>
									</span>
									<span class="mdl-list__item-secondary-action" style="display:inline;margin : 1px 1px 1px 1px;display:-moz-inline-box; display:inline-block;">
										<label class="demo-list-radio mdl-radio mdl-js-radio mdl-js-ripple-effect" for="tenpay">
											<input type="radio" id="tenpay" class="mdl-radio__button" name="type" value="tenpay" title="财付通">
											<span class="mdl-checkbox__label"><img src="./static/tenpay.ico">&nbsp;财付通&nbsp;&nbsp;</span>
										</label>
									</span>
								</div>

							</div>
							<div class="mdl-list__item-secondary-action" style="margin : 20px 0px 0px 0px;">
								<label class="mdl-js-ripple-effect" for="button">
									<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width:100%;" id="button" onclick="startbuy()">
										立即购买
									</button>
								</label>
							</div>
						</div>
					</div> 
				</div>
</body>
</html>  