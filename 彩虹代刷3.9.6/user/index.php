<?php
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($_GET['do']=='recharge'){
	$value=daddslashes($_GET['value']);
	$trade_no=date("YmdHis").rand(111,999);
	if(!is_numeric($value))exit('{"code":-1,"msg":"提交参数错误！"}');
	$sql="insert into `shua_pay` (`trade_no`,`tid`,`input`,`name`,`money`,`ip`,`addtime`,`status`) values ('".$trade_no."','-1','".$userrow['zid']."','在线充值余额','".$value."','".$clientip."','".$date."','0')";
	if($DB->query($sql)){
		exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","money":"'.$value.'","name":"在线充值余额"}');
	}else{
		exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
	}
}
$title = '平台首页';
include 'head.php';
?>
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
</style>
<div class="container" style="padding-top:70px;">
	<div class="row">
		<div class="modal inmodal fade" id="myModa4" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" onclick="clearInterval(interval1)"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span>
						</button>
						<h4 class="modal-title">订单信息</h4>
					</div>
					<div class="modal-body" id="showInfo2">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal" onclick="clearInterval(interval1)">关闭</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">我的信息</h2>
				</div>
				<div class="panel-body">
	<li class="list-group-item"><img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $userrow['qq']?$userrow['qq']:'10000';?>&spec=100" alt="Avatar" width="60" height="60" style="border:1px solid #FFF;-moz-box-shadow:0 0 3px #AAA;-webkit-box-shadow:0 0 3px #AAA;border-radius: 50%;box-shadow:0 0 3px #AAA;padding:3px;margin-right: 3px;margin-left: 6px;">&nbsp;&nbsp;<font color="orange"><?php echo $userrow['user']?> (UID:<?php echo $userrow['zid']?>)</font> <a href="uset.php?mod=user">[修改信息]</a></li>
	<li class="list-group-item">可用余额：<font color="red"><?php echo $userrow['rmb']?></font> 元&nbsp;<a href="#recharge">[充值]</a><?php if($conf['fenzhan_tixian']==1){?>&nbsp;<a href="tixian.php">[提现]</a><?php }?></li>
	<li class="list-group-item">注册时间：<font color="orange"><?php echo $userrow['addtime']?></font></li>
	<li class="list-group-item">你现在可以低价下单购买本站商品&nbsp;<a href="./shop.php">[进入自助下单]</a></li>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">分站信息</h2>
				</div>
				<div class="panel-body">
	<li class="list-group-item">绑定域名：<a href="http://<?php echo $userrow['domain']?>/" target="_blank" rel="noreferrer"><?php echo $userrow['domain']?></a></li>
	<li class="list-group-item">额外域名：<a href="http://<?php echo $userrow['domain2']?>/" target="_blank" rel="noreferrer"><?php echo $userrow['domain2']?></a></li>
	<li class="list-group-item">网站名称：<font color="green"><?php echo $userrow['sitename']?></font></li>
	<li class="list-group-item"><a href="uset.php?mod=site" class="btn btn-info form-control">修改网站信息</a></li>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default text-center" id="recharge">
				<div class="panel-heading">
					<h2 class="panel-title">在线充值余额</h2>
				</div>
				<div class="panel-body">
					<b>我当前的账户余额：<span style="font-size:16px; color:#FF6133;"><?php echo $userrow['rmb']?></span> 元</b>
					<hr>
					<input type="text" class="form-control" name="value" autocomplete="off" placeholder="输入要充值的余额"><br/>
<?php 
if($conf['alipay_api'])echo '<button type="submit" class="btn btn-default" id="buy_alipay"><img src="../assets/icon/alipay.ico" class="logo">支付宝</button>&nbsp;';
if($conf['qqpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_qqpay"><img src="../assets/icon/qqpay.ico" class="logo">QQ钱包</button>&nbsp;';
if($conf['wxpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_wxpay"><img src="../assets/icon/wechat.ico" class="logo">微信支付</button>&nbsp;';
if($conf['tenpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_tenpay"><img src="../assets/icon/tenpay.ico" class="logo">财付通</button>&nbsp;';
?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModa4" id="alink" style="visibility: hidden;"></button>
<hr><small style="color:#999;">付款后自动充值，刷新此页面即可查看余额。</small>
				</div>
			</div>
			<?php if(!empty($conf['gg_panel'])){?>
			<div class="panel panel-default text-center" id="recharge">
				<div class="panel-heading">
					<h2 class="panel-title">公告信息</h2>
				</div>
				<div class="panel-body">
					<?php echo $conf['gg_panel']?>
				</div>
			</div>
			<?php }?>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
$("#buy_alipay").click(function(){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){alert('充值金额不能为空');return false;}
	$.get("index.php?do=recharge&type=alipay&value="+value, function(data) {
		tishi_2('alipay',data);
	}, 'json');
});
$("#buy_qqpay").click(function(){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){alert('充值金额不能为空');return false;}
	$.get("index.php?do=recharge&type=qqpay&value="+value, function(data) {
		tishi_2('qqpay',data);
	}, 'json');
});
$("#buy_wxpay").click(function(){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){alert('充值金额不能为空');return false;}
	$.get("index.php?do=recharge&type=wxpay&value="+value, function(data) {
		tishi_2('wxpay',data);
	}, 'json');
});
$("#buy_tenpay").click(function(){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){alert('充值金额不能为空');return false;}
	$.get("index.php?do=recharge&type=tenpay&value="+value, function(data) {
		tishi_2('tenpay',data);
	}, 'json');
});
});
function tishi_2(paytype,d){
	if(d.code == 0){
		var data = '<p>订单号：'+d.trade_no+'</p><p>订单金额：<b>'+d.money+'</b>元</p><p>订单名称：'+d.name+'</p>'+
					'<p><b>付款后系统会自动为您充值到账，即时生效，无需卡密。</b></p>'+
					'<a href="../other/submit.php?type='+paytype+'&orderid='+d.trade_no+'" class="btn btn-success btn-block" target="_blank">立即支付</a>'+
					'</form>';
		var divshow = $("#showInfo2");
		divshow.text("");
		divshow.append(data);
		$("#alink").click();
	} else {
		alert(d.msg);
	}
}
</script>