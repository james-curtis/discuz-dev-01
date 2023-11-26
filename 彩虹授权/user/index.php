﻿﻿﻿<?php
/**
 * 授权平台
**/
$mod='blank';
include("../api.inc.php");
$title='授权平台';
$id=$user['id'];
$row = $DB->get_row("SELECT * FROM auth_site WHERE id='$id' limit 1");
$rs=$DB->get_row("SELECT * FROM auth_config WHERE 1");
$vip=$row['vip'];
$rmb=$row['rmb'];
if($vip==1){
	$dljg=$rs['vip1'];
	$v="<font color='#FFB200'>钻石代理</font>";
}elseif($vip==0){
	$dljg=$rs['vip0'];
	$v="<font color='#B26B00'>普通代理</font>";
}elseif($vip==2){
	$dljg=$rs['vip2'];
	$v="<font color='red'>至</font><font color='green'>尊</font><font color='#2D006B'>代</font><font color='#E60066'>理</font>";
}
$config = $DB->get_row("SELECT * FROM auth_config");
if(isset($_POST['submit'])){
$todaybegin=strtotime(date("Y-m-d",strtotime("-1 day"))." 00:00:00"); 
$todayend=date("Y-m-d",strtotime("-1 day"))." 23:59:59"; 
$myrow=$DB->get_row("select * from auth_site where id='{$user['id']}' limit 1");
if($myrow['dateline']>$todayend){
				exit("<script language='javascript'>alert('您今天已经签到,请勿重复签到！');window.location.href='index.php';</script>");
	}
	if($myrow['dateline']<$todayend){
			$sql="update `auth_site` set `dateline`='{$date}' where `id`='{$user['id']}'";
			if($DB->query($sql)){
if($user['dayes']==""){
				$DB->query("update `auth_site` set `dayes`=1 where `id`='{$user['id']}'");
}else{
				$DB->query("update `auth_site` set `dayes`=`dayes`+1 where `id`='{$user['id']}'");
}
				$DB->query("update `auth_site` set `user_jf`=`user_jf`+1 where `id`='{$user['id']}'");
			exit("<script language='javascript'>alert('签到成功,获得1积分！');window.location.href='index.php';</script>");
	}else{
		exit("<script language='javascript'>alert('签到失败！');window.location.href='index.php';</script>");
	}
		}
}
if (!$userlogin)
{
    exit('<script>alert("请先登录!");window.location.href="./login.php";</script>');
}
?>
<?php
$blocks=$DB->count("SELECT count(*) from auth_block WHERE 1");
$config=$DB->count("SELECT count(*) from auth_config WHERE 1");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?=$title?></title>
  <meta name="keywords" content="授权平台,授权平台"/>
  <meta name="description" content="授权平台授权平台"/>
  <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
    <script src="http://libs.useso.com/js/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<script language="javascript">
  function logout(){
  if( confirm("你确实要退出吗？？")){
   window.parent.location.href="./login.php?userlogout";
  }
  else{
   return;
  }
 }
</script>
  <nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">导航按钮</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="./">授权平台</a>
      </div><!-- /.navbar-header -->
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="<?php echo checkIfActive('index,')?>">
		  <a href="./"><span class="glyphicon glyphicon-home"></span> 平台首页</a>
          </li>
          <li>
            <li class="<?php echo checkIfActive('newbuy,kmsq,list,add')?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cloud"></span> 授权管理<b class="caret"></b></a>
            <ul class="dropdown-menu">
			  <li><a href="./add.php">添加站点</a><li>
              <li><a href="./kmsq.php">卡密授权</a><li>
              <li><a href="./list.php">授权列表</a><li>
              <li><a href="./newbuy.php">在线授权</a></li>
            </ul>
          </li>
		  <li class="<?php echo checkIfActive('downfile')?>"><a href="./downfile.php"><span class="glyphicon glyphicon-thumbs-up"></span> 下载管理</a></li>
		  <li>
		  <li class="<?php echo checkIfActive('tglj')?>"><a href="./tglj.php"><span class="glyphicon glyphicon-list"></span> 域名防红</a></li>
		  <li>
		  <li class="<?php echo checkIfActive('uset')?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> 系统设置<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./uset.php?mod=user">用户资料设置</a></li>
            </ul>
          </li>
          <li><a href="javascript:logout()"><span class="glyphicon glyphicon-log-out"></span> 退出登陆</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->
  <div class="container" style="padding-top:70px;">
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">我的信息</h2>
				</div>
				<div class="panel-body">
	<li class="list-group-item"><img src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $userrow['qq']?$userrow['qq']:'10000';?>&spec=100" alt="Avatar" width="60" height="60" style="border:1px solid #FFF;-moz-box-shadow:0 0 3px #AAA;-webkit-box-shadow:0 0 3px #AAA;border-radius: 50%;box-shadow:0 0 3px #AAA;padding:3px;margin-right: 3px;margin-left: 6px;">&nbsp;&nbsp;<font color="orange"><?php echo $user['user']?> (UID:<?php echo $user['id']?>)</font> <a href="uset.php?mod=user">[修改信息]</a></li>
	<li class="list-group-item">当前余额：<font color="red"><?php echo $rmb."元";?></font><a href="./cz.php">[充值]</a>&nbsp;<a href="javascript:alert('等待白云更新');">[购买卡密充值]</a>&nbsp;<a href="./kmlist.php">[卡密生成]</a></li>
	<li class="list-group-item">当前积分：<font color="red"><?php echo $user['user_jf']?></font></li>
	<li class="list-group-item">QQ邮箱：<font color="orange"><?php echo $user['uid']?>@qq.com</font></li>
	<li class="list-group-item">拿卡价格：<font color="red"><?php echo $dljg."元";?></font></li>
	<li class="list-group-item">账号登录时间：<font color="orange"><? echo $user['date']?></font></li>
	<li class="list-group-item">代理VIP等级：<font color="orange"><?php echo $v;?></font></li>
	<li class="list-group-item"><span>最后一次签到时间:<font color="red">&nbsp;<?php echo $user['dateline']?></font></span></li>
	<li class="list-group-item"><span>累计签到:<span><?php echo $user['dayes']?></span>&nbsp;天</span></li>
	<form method="post" class="form-horizontal">
	<br/>	
	<button class="btn btn-info form-control" name="submit"  type="submit">立即签到</button>
		</li>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">用户信息</h2>
				</div>
				<div class="panel-body">
	<li class="list-group-item">UID：<font color="red"><?php echo $user['id']?></font></li>
	<li class="list-group-item">授权商UID：<font color="red"><?php echo $user['zid']?></font></li>
	<li class="list-group-item">用户名：<font color="green"><?php echo $user['user']?></font></li>
	<li class="list-group-item">用户QQ：<font color="orange"><?php echo $user['uid']?></font></li>
	<li class="list-group-item">现在时间：<font color="orange"><?=$date?></font></li>
	<li class="list-group-item">当前余额：<font color="orange"><?php echo $user['rmb']?></font></li>
	<li class="list-group-item">当前积分：<font color="orange"><?php echo $user['user_jf']?></font></li>
	<form method="post" class="form-horizontal">
	<br/>	
	<a href="./uset.php?mod=user" class="btn btn-info form-control">修改用户资料</a></li>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<div class="panel panel-default text-center" id="recharge">
				<div class="panel-heading">
					<h2 class="panel-title">用户公告</h2>
				</div>
				<div class="panel-body">
<?php 
 $rs=$DB->query("SELECT * FROM auth_gl WHERE 1 order by id desc ");
 while($res = $DB->fetch($rs))
 {
  echo "<div class='gl'>";
  echo "<a href='wz.php?id={$res['id']}' ><p class='bt' id='".$res['id']."'>".$res['title']."</p></a>";
  echo "</div>";
 }
 ?>
			</div>
		</div>
		
	</div>
</div>