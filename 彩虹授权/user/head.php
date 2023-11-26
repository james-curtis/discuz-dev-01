﻿<?php
@header('Content-Type: text/html; charset=UTF-8');
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
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">