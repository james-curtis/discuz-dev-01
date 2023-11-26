<?php
$mod='blank';
include("../../api.inc.php");
?>
<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>授权商查询</title>
    <!--baidu-->
    <meta name="baidu-site-verification" content="4IPJiuihDj" />
    <!-- Bootstrap -->
    <link href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.css" rel="stylesheet">
    <script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
      <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
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
</head>
<body background="../root/images/fzbeijing.png">
<div class="container">
    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
          <li role="presentation" class="active"><a href="/">返回首页</a></li>
          <li role="presentation"><a href="./pay.php">购买授权</a></li>
        </ul>
        <h3 class="text-muted" align="left">授权商查询</h3>
     </div><hr>
	 <h3 class="form-signin-heading">请输入授权商QQ</h3>
	 <form action="?" class="form-sign" method="get">
	 比如：小杰1503816935、白云760611885
	 <input type="text" class="form-control" name="qq" value=""><br>
	 <input type="submit" class="btn btn-primary btn-block" name="submit" value="点击查询"><br/>
<?php
if($qq=$_GET['qq']) {
	$qq=$_GET['qq'];
	$row=$DB->get_row("SELECT * FROM auth_user WHERE qq='$qq' limit 1");
	echo '<label>查询QQ：</label>'.$qq.'&nbsp;<a href="tencent://message/?uin='.$qq.'&amp;Site=授权平台&amp;Menu=yes"><img src="http://wpa.qq.com/pa?p=2:'.$qq.':41" border=0></a><br>';
	if($row) {
		echo '<div class="alert alert-success"><img src="static/ico_success.png">查询结果：正版授权商，请放心交易！</div>';
	}else{
		echo '<div class="alert alert-danger"><img src="static/ico_tip.png">查询结果：盗版授权商，请谨慎交易！</div>';
	}
}
$DB->close();
?>
  <hr><div class="container-fluid">
  <a href="/pay.php" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-credit-card"></span>购买</a>
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-exclamation-sign"></span> 帮助</a> 
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-info btn-default btn-sm"><span class="glyphicon glyphicon-user"></span> 客服</a>
  <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span> 反馈</a>
</div>
<p style="text-align:center"><br>&copy; Powered by <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2">白云&小杰</a>!</p></div>
</body>
</html>