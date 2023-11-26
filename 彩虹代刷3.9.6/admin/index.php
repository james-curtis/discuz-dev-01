<?php
/**
 * 自助下单系统
**/
include("../includes/common.php");
$title='自助下单系统管理中心';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<?php
$count1=$DB->count("SELECT count(*) from shua_orders");
$count2=$DB->count("SELECT count(*) from shua_orders where status=1");
$count3=$DB->count("SELECT count(*) from shua_site");

$thtime=date("Y-m-d").' 00:00:00';
$count4=$DB->count("SELECT sum(point) from shua_points where action='提成'");
$count6=$DB->count("SELECT sum(point) from shua_points where action='提成' and addtime>='$thtime'");

$count5=$DB->count("SELECT count(*) from shua_site where addtime>='$thtime'");
$count7=$DB->count("SELECT sum(rmb) from shua_site where status=1");

$count8=$DB->count("SELECT count(*) from shua_orders where addtime>='$thtime'");

$count9=$DB->count("SELECT sum(money) from shua_pay where tid!=-1 and addtime>='$thtime' and status=1");

$mysqlversion=$DB->count("select VERSION()");
$strtotime=strtotime($conf['build']);//获取开始统计的日期的时间戳
$now=time();//当前的时间戳
$yxts=ceil(($now-$strtotime)/86400);//取相差值然后除于24小时(86400秒)
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-sm-12 col-md-3">
		<div class="list-group">
			<div class="list-group-item list-group-item-success">
				<h3 class="panel-title">数据管理</h3>
			</div>
			<a class="list-group-item" href="./list.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;订单管理</span></a>
			<a class="list-group-item" href="./classlist.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;分类管理</span></a>
			<a class="list-group-item" href="./shoplist.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;商品管理</span></a>
			<a class="list-group-item" href="./kmlist.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;卡密管理</span></a>
			<a class="list-group-item" href="./sitelist.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;分站列表</span></a>
			<a class="list-group-item" href="./record.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;收支明细</span></a>
			<?php if($conf['fenzhan_tixian']==1){?><a class="list-group-item" href="./tixian.php"><span class="glyphicon glyphicon-list" aria-hidden="true">&nbsp;余额提现</span></a><?php }?>
		</div>
		<div class="list-group">
			<div class="list-group-item list-group-item-success">
				<h3 class="panel-title">系统设置</h3>
			</div>
			<a class="list-group-item" href="./set.php?mod=site"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;网站信息配置</span></a>
			<a class="list-group-item" href="./set.php?mod=gonggao"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;网站公告配置</span></a>
			<a class="list-group-item" href="./shequlist.php"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;社区对接配置</span></a>
			<a class="list-group-item" href="./set.php?mod=mail"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;发信邮箱配置</span></a>
			<a class="list-group-item" href="./set.php?mod=pay"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;支付接口配置</span></a>
			<a class="list-group-item" href="./set.php?mod=upimg"><span class="glyphicon glyphicon-edit" aria-hidden="true">&nbsp;网站Logo上传</span></a>
		</div>
		<div class="list-group">
			<div class="list-group-item list-group-item-success">
				<h3 class="panel-title">其他功能</h3>
			</div>
			<a class="list-group-item" href="./update.php"><span class="glyphicon glyphicon-check" aria-hidden="true">&nbsp;检查版本更新</span></a>
		</div>
	</div>
    <div class="col-sm-12 col-md-9 center-block">
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">后台管理首页</h3></div>
<table class="table table-bordered">
<tbody>
<tr height="25">
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>业务订单总数</b></br><?php echo $count1?>条</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>已处理的订单</b></br></span><?php echo $count2?>条</font></td>
</tr>
<tr height="25">
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-time"></i>平台已经运营</b></br><?php echo $yxts?>天</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-globe"></i>分站数量</b></span></br><?php echo $count3?>个</font></td>
</tr>
<tr height="25">
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>今日订单数</b></br><?php echo $count8?>条</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-usd"></i>今日交易额</b></br></span><?php echo round($count9,2)?>元</font></td>
</tr>
<tr height="25">
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-check"></span>今日分站提成</b></br><?php echo round($count6,2)?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-globe"></i>今日新开分站</b></br></span><?php echo $count5?>个</font></td>
</tr>
<tr height="25">
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-usd"></i>分站总提成</b></br><?php echo round($count4,2)?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-gbp"></i>分站总资金</b></span></br><?php echo round($count7,2)?>元</font></td>
</tr>
<tr height="25">
<td align="center"><a href="../" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-home"></i>网站首页</a></td>
<td align="center"><a href="./login.php?logout" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-log-out"></i>退出登录</a></td>
</tr>
</tbody>
</table>
      </div>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">服务器信息</h3>
	</div>
	<ul class="list-group">
		<li class="list-group-item">
			<b>PHP 版本：</b><?php echo phpversion() ?>
			<?php if(ini_get('safe_mode')) { echo '线程安全'; } else { echo '非线程安全'; } ?>
		</li>
		<li class="list-group-item">
			<b>MySQL 版本：</b><?php echo $mysqlversion ?>
		</li>
		<li class="list-group-item">
			<b>服务器软件：</b><?php echo $_SERVER['SERVER_SOFTWARE'] ?>
		</li>
		
		<li class="list-group-item">
			<b>程序最大运行时间：</b><?php echo ini_get('max_execution_time') ?>s
		</li>
		<li class="list-group-item">
			<b>POST许可：</b><?php echo ini_get('post_max_size'); ?>
		</li>
		<li class="list-group-item">
			<b>文件上传许可：</b><?php echo ini_get('upload_max_filesize'); ?>
		</li>
	</ul>
</div>
    </div>
  </div>