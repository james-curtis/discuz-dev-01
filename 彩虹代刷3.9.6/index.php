<?php
include("./includes/common.php");
include("./includes/txprotect.php");
$qq=isset($_GET['qq'])?strip_tags($_GET['qq']):null;

$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;

$rs=$DB->query("SELECT * FROM shua_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$shua_class[0]='默认分类';
while($res = $DB->fetch($rs)){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}

$select2='<option value="0">请选择商品</option>';

if($is_fenzhan==true && file_exists(ROOT.'assets/img/logo_'.$conf['zid'].'.png')){
	$logo = 'assets/img/logo_'.$conf['zid'].'.png';
}else{
	$logo = 'assets/img/logo.png';
}
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?php echo $conf['sitename']?> - <?php echo $conf['title']?></title>
  <meta name="keywords" content="<?php echo $conf['keywords']?>">
  <meta name="description" content="<?php echo $conf['description']?>">
  <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
body{
background:#ecedf0 url("assets/img/bj.png") fixed;
background-repeat:repeat;}
</style>
</head>
<body>
<div class="modal fade" align="left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $conf['sitename']?></h4>
      </div>
      <div class="modal-body">
	  <?php echo $conf['modal']?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
<div class="panel panel-default">
	<div class="panel-body" style="text-align: center;">
		<img src="<?php echo $logo?>">
	</div>
</div>
<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>站点公告</b></font></h3></div>
<?php echo $conf['anounce']?>
</div>

<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<b>自助下单</b></font></h3></div>
	<ul class="nav nav-tabs">
		<li class="active"><a href="#onlinebuy" data-toggle="tab">在线支付下单</a></li><li><a href="#cardbuy" data-toggle="tab" <?php if($conf['iskami']==0){?>style="display:none;"<?php }?>>卡密下单</a></li><li><a href="#query" data-toggle="tab">进度查询</a></li><li><a href="#lqq" data-toggle="tab" <?php if(empty($conf['lqqapi'])){?>style="display:none;"<?php }?>>拉圈圈赞</a></li><li><a href="#chat" data-toggle="tab" <?php if(empty($conf['chatframe'])){?>style="display:none;"<?php }?>>聊天交流</a></li>
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
<?php
if($conf['alipay_api'])echo '<button type="submit" class="btn btn-default" id="buy_alipay"><img src="assets/icon/alipay.ico" class="logo">支付宝</button>&nbsp;';
if($conf['qqpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_qqpay"><img src="assets/icon/qqpay.ico" class="logo">QQ钱包</button>&nbsp;';
if($conf['wxpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_wxpay"><img src="assets/icon/wechat.ico" class="logo">微信支付</button>&nbsp;';
if($conf['tenpay_api'])echo '<button type="submit" class="btn btn-default" id="buy_tenpay"><img src="assets/icon/tenpay.ico" class="logo">财付通</button>&nbsp;';
?>
			</div>
			<input type="submit" id="submit_buy" class="btn btn-primary btn-block" value="立即购买">
		</div>
		<div class="tab-pane fade in" id="cardbuy">
			<?php if(!empty($conf['kaurl'])){?>
			<div class="form-group">
				<a href="<?php echo $conf['kaurl']?>" class="btn btn-default btn-block" target="_blank"/>点击进入购买卡密</a>
			</div>
			<?php }?>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">输入卡密</div>
				<input type="text" name="km" id="km" value="" class="form-control" required/>
			</div></div>
			<input type="submit" id="submit_checkkm" class="btn btn-primary btn-block" value="检查卡密">
			<div id="km_show_frame" style="display:none;">
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">商品名称</div>
				<input type="text" name="name" id="km_name" value="" class="form-control" disabled/>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon" id="km_inputname">下单ＱＱ</div>
				<input type="text" name="inputvalue" id="km_inputvalue" value="<?php echo $qq?>" class="form-control" required/>
			</div></div>
			<div id="km_inputsname"></div>
			<div id="km_alert_frame" class="alert alert-warning" style="display:none;"></div>
			<input type="submit" id="submit_card" class="btn btn-primary btn-block" value="立即购买">
			<div id="result1" class="form-group text-center" style="display:none;">
			</div>
			</div>
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
				<thead><tr><th>下单账号</th><th>商品名称</th><th>数量</th><th>购买时间</th><th>状态</th></tr></thead>
				<tbody id="list">
				</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade in" id="lqq">
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon">请输入QQ</div>
				<input type="text" name="qq" id="qq4" value="" class="form-control" required/>
			</div></div>
			<input type="submit" id="submit_lqq" class="btn btn-primary btn-block" value="立即提交">
			<div id="result3" class="form-group text-center" style="display:none;"></div>
		</div>
		<div class="tab-pane fade in" id="chat">
			<?php echo $conf['chatframe']?>
		</div>
		</div>
	</div>
</div>

<?php
$strtotime=strtotime($conf['build']);//获取开始统计的日期的时间戳
$now=time();//当前的时间戳
$yxts=ceil(($now-$strtotime)/86400);//取相差值然后除于24小时(86400秒)
$count1=$DB->count("SELECT count(*) from shua_orders");
$count2=$DB->count("SELECT count(*) from shua_orders where status>=1");
?>
<div class="panel panel-info">
<div class="list-group-item reed" style="background:#64b2ca;"><h3 class="panel-title"><font color="#fff"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;<b>运行日志</b></font></h3></div>
<table class="table table-bordered">
<tbody>
<tr>
	<td align="center"><font color="#808080"><b>平台已经运营</b></br><i class="fa fa-hourglass-2 fa-2x"></i></br><?php echo $yxts?>天</font></td>
	<td align="center"><font color="#808080"><b>业务订单总数</b></br><span class="fa fa-shopping-cart fa-2x"></span></br><?php echo $count1?>条</font></td>
</tr>
<tr height=50>
         <td align="center"><font color="#808080"><b>已处理的订单</b></br><i class="fa fa-check-square-o fa-2x"></i></span></br><?php echo $count2?>条</font></td>
	<td align="center"><font color="#808080"><b>您共访问本站</b></br><i class="fa fa-internet-explorer fa-2x"></i></span></br><span id="counter">1</span>次</font></td>
<tbody>
</table>
</div>

<div class="panel panel-info">
<?php echo $conf['bottom']?>
<center><a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['qq']?$conf['qq']:$conf['kfqq']?>&site=qq&menu=yes" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-user"></span> 联系客服</a></center>
</div>
<p style="text-align:center"><br>&copy; Powered by <a href="http://www.cccyun.cc">彩虹</a>!</p></div>
</div>

<script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>

<script type="text/javascript">
var isModal=<?php echo empty($conf['modal'])?'false':'true';?>;
var ui_bing=<?php echo ($conf['ui_bing']==1)?'true':'false';?>;
var hashsalt='<?php echo $addsalt?>';
</script>
<script src="assets/js/main.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>