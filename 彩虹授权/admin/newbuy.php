<?php
/**
 * 在线授权
**/
$mod='blank';
include("../api.inc.php");
$title='在线授权';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="col-lg-8 col-md-12 col-lg-offset-2 text-center">
<div class="panel panel-info">
      <div class="panel-heading font-bold">在线授权</div>
        <div class="panel-body">
			<form class="form-horizontal" action="../other/submit.php" method="post" target="_blank">
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
				<label><input type="radio" name="type" value="alipay" checked="">支付宝</label>&nbsp;<label><input type="radio" name="type" value="qqpay">QQ钱包</label>&nbsp;<label><input type="radio" name="type" value="wxpay">微信支付</label>
				<div class="form-group">
					<button type="submit" class="btn btn-success">确定支付</button>
            </div>
          </form>
        </div>