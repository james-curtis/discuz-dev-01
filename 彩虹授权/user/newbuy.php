<?php
/**
 * 在线授权
**/
$mod='blank';
include("../api.inc.php");
$title='在线授权';
include './head.php';
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">在线授权</h2>
				</div>
				<div class="panel-body">
			<form class="form-horizontal" action="/other/ptsubmit.php" method="post" target="_blank">
				<div class="input-group">
				<span class="input-group-addon">订单号</span>
				<input type="text" class="form-control" name="out_trade_no" value="<?php echo date("YmdHis").rand(100000,999999); ?>" readonly = "readonly">
				</div><br/>
				<div class="input-group">
				<span class="input-group-addon">授权域名</span>
				<input type="text"  class="form-control" name="url" placeholder="需要授权的域名" required>
				</div><br/>
				<div class="input-group">
				<span class="input-group-addon">授权Q Q</span>
				<input type="text"  class="form-control" name="qq" placeholder="需要授权的QQ" required>
				</div><br/>
              <div class="input-group-addon">
				<label><input type="radio" name="type" value="alipay" checked="">支付宝</label>&nbsp;<label><input type="radio" name="type" value="qqpay">QQ钱包</label>&nbsp;<label><input type="radio" name="type" value="wxpay">微信支付</label>&nbsp;<label><input type="radio" name="type" value="tenpay">财付通</label>
				</div><br/>
				<div class="form-group">
                  <button class="btn btn-info btn-block" type="submit" name="submit">确定支付</button>
            </div>
          </form>
        </div>