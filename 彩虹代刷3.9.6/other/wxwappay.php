<?php
require 'inc.php';

@header('Content-Type: text/html; charset=UTF-8');

$trade_no=daddslashes($_GET['trade_no']);
$sitename=daddslashes($_GET['sitename']);
$row=$DB->get_row("SELECT * FROM shua_pay WHERE trade_no='{$trade_no}' limit 1");
if(!$row)exit('该订单号不存在，请返回来源地重新发起请求！');

require_once SYSTEM_ROOT."wxpay/WxPay.Api.php";
require_once SYSTEM_ROOT."wxpay/WxPay.NativePay.php";
$notify = new NativePay();
$input = new WxPayUnifiedOrder();
$input->SetBody($row['name']);
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($row['money']*100);
$input->SetSpbill_create_ip($clientip);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url($siteurl.'wxpay_notify.php');
if($conf['wxpay_api']==3){
	$input->SetTrade_type("MWEB");
}else{
	$input->SetTrade_type("NATIVE");
}
$result = $notify->GetPayUrl($input);
if($result["result_code"]=='SUCCESS'){
	if($conf['wxpay_api']==3){
		$redirect_url=$siteurl.'wxwap_return.php?trade_no='.$trade_no;
		$url=$result['mweb_url'].'&redirect_url='.urlencode($redirect_url);
		exit("<script>window.location.replace('{$url}');</script>");
	}else{
		$code_url = $result['code_url'];
	}
}else{
	sysmsg('微信支付下单失败！['.$result["err_code"].'] '.$result["err_code_des"]);
}
?>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>微信支付手机版</title>
  <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
<style type="text/css">
.qr-text {padding: 30px;margin: 5px 0;background-color: #FDFDCA;border-radius: 3px;border: 1px solid #EEEEEE;word-wrap: break-word;word-break: break-all;}
</style>
</head>
<body>

<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-default">
	<div class="panel-heading" style="text-align: center;"><h3 class="panel-title">
		微信支付手机版
	</div>
		<div class="list-group" style="text-align: center;">
			<div class="list-group-item"><img src="assets/img/wxwappay.png"/><br/>
			<span style="font-size: 28px;text-align: center;">￥<?php echo $row['money']?></span></div>
			<div class="list-group-item list-group-item-info">请复制以下支付链接到微信打开：</div>
			<div class="list-group-item">
			<p class="qr-text"><strong><?php echo $code_url?></strong></p>
			</div>
			<div class="list-group-item">提示：你可以将以上链接发到自己微信的聊天框（在微信顶部搜索框可以搜到自己的微信号），即可点击进入支付</div>
			<div class="list-group-item">支付成功请返回当前页面查看结果</div>
			<div class="list-group-item"><button class="btn btn-success btn-block" onclick="window.location.href='weixin://';">打开微信</button></div>
			<div class="list-group-item"><button class="btn btn-success btn-block" onclick="loadmsg()">刷新支付结果</button></div>
			<div class="list-group-item"><a class="btn btn-default btn-block" href="wxpay.php?trade_no=<?php echo $trade_no?>&sitename=<?php echo urlencode($_GET['sitename'])?>">电脑用户？点此进入扫码支付</a></div>
		</div>
</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script>
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $row['trade_no']?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					if (confirm("您已支付完成，需要跳转到订单页面吗？")) {
                        window.location.href=data.backurl;
                    } else {
                        // 用户取消
                    }
                }else{
                    setTimeout("loadmsg()", 4000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
</body>
</html>