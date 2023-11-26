<?php
@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?=$title?></title>
  <meta name="keywords" content="授权平台授权平台"/>
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
   window.parent.location.href="/login.php?logout";
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
        <a class="navbar-brand" href="./">白云&小杰授权平台V2.0</a>
      </div><!-- /.navbar-header -->
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="<?php echo checkIfActive('index,') ?>">
            <a href="./"><span class="glyphicon glyphicon-home"></span> 平台首页</a>
          </li>
          <li>
              <?php if($udata['per_sq']==1&&$udata['per_db']==1&&$udata['active']==1){ ?>
            <li class="<?php echo checkIfActive('pusGl,daili,userlist,adduser,log') ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> 用户管理<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./pusGl.php">发布公告</a><li>
              <li><a href="./daili.php">代理用户管理</a><li>
              <li><a href="./userlist.php">用户列表</a></li>
              <li><a href="./adduser.php">添加用户</a><li>
              <li><a href="./log.php">操作记录</a><li>
            </ul>
          </li>
          <li>
              <?php } if($udata['per_db']==1){ ?>
            <li class="<?php echo checkIfActive('list,add,kmlist,km,search') ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cloud"></span> 授权管理<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./list.php">授权列表</a></li>
              <li><a href="./add.php">添加授权</a><li>
              <li><a href="./addsite.php">添加站点</a><li>
              <li><a href="./kmlist.php">授权卡密生成</a><li>
              <li><a href="./km.php">余额卡密生成</a><li>
              <li><a href="./search.php">搜索授权</a><li>
            </ul>
          </li>
            <?php } ?>
            <li class="<?php echo checkIfActive('downfile')?>">
             <a href="./downfile.php"><span class="glyphicon glyphicon-thumbs-up"></span> 下载管理</a>
			 </li>
          <li>
          <?php if($udata['per_sq']==1&&$udata['active']==1){ ?>
            <li class="<?php echo checkIfActive('pirate,getpwd,jm') ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-globe"></span> 盗版管理<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./pirate.php">站点列表</a></li>
              <li><a href="./jm.php">源码加密</a></li>
              <li><a href="./getpwd.php">获取密码</a><li>
            </ul>
          </li>
          <li>
          <?php } if($udata['uid']==1){ ?>
            <li class="<?php echo checkIfActive('set,pay,order') ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> 系统设置<b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="./set.php">设置信息</a></li>
              <li><a href="./pay.php">支付设置</a><li>
              <li><a href="./order.php">订单记录</a><li>
            </ul>
          </li>
          <?php } ?>
          <li><a href="javascript:logout()"><span class="glyphicon glyphicon-log-out"></span> 注销登陆</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->