<?php
/**
 * 自助下单
**/
include("../includes/common.php");
$title='自助下单';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($_GET['act']=='submit'){
	$orderid=isset($_GET['orderid'])?daddslashes($_GET['orderid']):exit('No orderid!');
	$srow=$DB->get_row("SELECT * FROM shua_pay WHERE trade_no='{$orderid}' limit 1");
	if(!$srow['trade_no'])exit("<script language='javascript'>alert('订单号不存在！');window.location.href='shop.php';</script>");
	if($srow['status']==0){
		if($srow['money']>$userrow['rmb'])exit("<script language='javascript'>alert('你的余额不足，请充值！');window.location.href='shop.php';</script>");
		$DB->query("update `shua_pay` set `status` ='1',`endtime` ='$date' where `trade_no`='{$orderid}'");
		$DB->query("update `shua_site` set `rmb`=`rmb`-{$srow['money']} where `zid`='{$userrow['zid']}'");
		addPointRecord($userrow['zid'], $srow['money'], '消费', '购买 '.$srow['name']);
		processOrder($srow);
	}
	exit("<script language='javascript'>alert('您所购买的商品已付款成功，感谢购买！');window.location.href='shop.php';</script>");
}
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<?php

$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$shua_class[0]='默认分类';
while($res = $DB->fetch($rs)){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}

$select2='<option value="0" id="moren">请选择商品</option>';
?>
<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<b>自助下单</b></font></h3></div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#onlinebuy" data-toggle="tab">在线下单</a></li><li><a href="#query" data-toggle="tab">进度查询</a></li>
	</ul>
	<div class="list-group-item">
		<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="onlinebuy">
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">选择分类</div>
				<select name="tid" id="cid" class="form-control"><?php echo $select?></select>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">选择商品</div>
				<select name="tid" id="tid" class="form-control" onchange="getPoint();"><?php echo $select2?></select>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">商品价格</div>
				<input type="text" name="need" id="need" class="form-control" disabled/>
			</div></div>
			<div class="form-group" id="display_num" style="display:none;">
				<div class="input-group"><div class="input-group-addon">数量</div>
				<div class="input-group-addon"><input id="num_min" type="button" value="-"/></div>
				<div class="input-group-addon"><input id="num" name="num" class="form-control" type="number" min="1" value="1"/></div>
				<div class="input-group-addon"><input id="num_add" type="button" value="+"/></div>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon" id="inputname">下单ＱＱ</div>
				<input type="text" name="inputvalue" id="inputvalue" value="<?php echo $qq?>" class="form-control" required/>
			</div></div>
			<div id="inputsname"></div>
			<div id="alert_frame" class="alert alert-warning" style="display:none;"></div>
			<div id="pay_frame" class="form-group text-center" style="display:none;">
			<div class="form-group">
				<div class="input-group">
				<div class="input-group-addon">订单号</div>
				<input class="form-control" name="orderid" id="orderid" value="" disabled>
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
				<div class="input-group-addon">共需支付</div>
				<input class="form-control" name="needs" id="needs" value="" disabled>
				</div>
			</div>
			<div class="alert alert-success">订单保存成功，请点击以下链接支付！</div>
			<button type="submit" class="btn btn-primary btn-block" id="buy_shop">使用余额支付</button>
			</div>
			<input type="submit" id="submit_buy" class="btn btn-primary btn-block" value="立即购买">
		</div>
		<div class="tab-pane fade in" id="query">
			<div class="alert alert-info" <?php if(empty($conf['gg_search'])){?>style="display:none;"<?php }?>><?php echo $conf['gg_search']?></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">查询内容</div>
				<input type="text" name="qq" id="qq3" value="<?php echo $qq?>" class="form-control" placeholder="请输入下单的QQ（留空则根据浏览器缓存查询）" required/>
			</div></div>
			<input type="submit" id="submit_query" class="btn btn-primary btn-block" value="立即查询">
			<div id="result2" class="form-group" style="display:none;">
				<table class="table table-striped">
				<thead><tr><th>ＱＱ</th><th>商品名称</th><th>数量</th><th>购买时间</th><th>状态</th></tr></thead>
				<tbody id="list">
				</tbody>
				</table>
			</div>
		</div>
		</div>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>
<script type="text/javascript">
var isModal=false;
var ui_bing=false;
</script>
<script src="../assets/js/main.js?ver=<?php echo VERSION ?>"></script>