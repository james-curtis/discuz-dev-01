﻿<?php
include("../../api.inc.php");
if($_POST['do'] == 'do'){
	$km = $_POST['km'];
	$qq = $_POST['qq'];
	$url = $_POST['url'];
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$date = date("Y-m-d H-i-s");
	$row1=$DB->get_row("SELECT * FROM auth_site WHERE 1 order by sign desc limit 1");
	$row2=$DB->get_row("SELECT * FROM auth_site WHERE uid='{$qq}' limit 1");
	$row3=$DB->get_row("SELECT * FROM auth_site WHERE url='{$url}' limit 1");
	$row4=$DB->get_row("SELECT * FROM auth_site WHERE user='{$user}' limit 1");
	$row5=$DB->get_row("SELECT * FROM auth_site WHERE pass='{$pass}' limit 1");
	$sign=$row1['sign']+1;
	$authcode=md5(random(32).$qq);
	$row=$DB->get_row("SELECT * FROM auth_site WHERE user='{$user}' limit 1");
	if($row!='')exit("<script language='javascript'>alert('授权平台已存在该账号，请使用别的');history.go(-1);</script>");
	$row = $DB->get_row("SELECT * FROM auth_km WHERE km = '{$km}'");
	if($km == '' or $qq == '' or $url =='' or $user =='' or $pass ==''){
		exit("<script language='javascript'>alert('所有项不能留空！');history.go(-1);</script>");
	}
	if(!$row){
		exit("<script language='javascript'>alert('此卡密不存在！');history.go(-1);</script>");
	}else if($row['zt'] == '0'){
		exit("<script language='javascript'>alert('此卡密已使用！');history.go(-1);</script>");
	}else if($row3 != ''){
		exit("<script language='javascript'>alert('平台已存在此域名！');history.go(-1);</script>");
	}else if($row2 != ''){
		$DB->query("update auth_km set zt = '0' where id='{$row['id']}'");
		$DB->query("INSERT INTO auth_site (`uid`, `user`, `pass`, `rmb`, `vip`, `url`, `date`, `authcode`, `sign`,`active`) VALUES ('$qq', '$user', '$user', '0', '0', '$url', '$date', '".$row2['authcode']."', '".$row2['sign']."', '1')");
		exit("<script language='javascript'>alert('授权成功！');history.go(-1);</script>");
	}else{
		$DB->query("update auth_km set zt = '0' where id='{$row['id']}'");
		$DB->query("INSERT INTO auth_site (`uid`, `user`, `pass`, `rmb`, `vip`, `url`, `date`, `authcode`, `sign`,`active`) VALUES ('$qq', '$user', '$user', '0', '0', '$url', '$date', '$authcode', '$sign', '1')");
		exit("<script language='javascript'>alert('授权成功！');window.location.href='/get';</script>");
	}
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>卡密授权</title>
  <meta name="keywords" content=""/>
  <meta name="description" content=""/>
  <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>
	<style>
	body{
		margin: 0 auto;
		text-align: center;
	}
	.container {
	  max-width: 580px;
	  padding: 15px;
	  margin: 0 auto;
	}
	</style>
	<script type="text/javascript">
	  function getValue(obj,str){
	  var input=window.document.getElementById(obj);
	  input.value=str;
	  }
  </script>
  <body>
<body background="../root/images/fzbeijing.png">
<div class="container">    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
          <li role="presentation" class="active"><a href="/">返回首页</a></li>
          <li role="presentation"><a href="./pay.php">购买授权</a></li>
        </ul>
        <h3 class="text-muted" align="left">卡密授权</h3>
     </div><hr>
	 <form class="form-horizontal" method="post" action="">
	 <input type="hidden" name="do" value="do">
	 授权域名(不要带http://)
	 <input type="text" class="form-control" name="url" placeholder="授权域名(不要带http://)"><br>
	 授权QQ
	 <input type="text" class="form-control" name="qq" placeholder="请输入要授权的QQ号码"><br>
	 授权卡密
	 <input type="text" class="form-control" name="km" placeholder="请输入要授权的卡密"><br>
	 注册账号
	 <input type="text" class="form-control" name="user" placeholder="请输入账号(可以随意输入)"><br>
	 注册密码
	 <input type="text" class="form-control" name="pass" placeholder="请输入密码(可以随意输入)"><br>
	 <input type="submit" class="btn btn-primary btn-block" name="submit" value="点击授权"><br/>
	 <hr><div class="container-fluid">
  <a href="/pay.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-credit-card"></span>购买</a>
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-exclamation-sign"></span> 帮助</a> 
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-info btn-default btn-sm"><span class="glyphicon glyphicon-user"></span> 客服</a>
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span> 反馈</a>
</div>
<p style="text-align:center"><br>&copy; Powered by <a href="../">白云&小杰</a>!</p></div>
</body>
</html>