﻿<?php
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
    <title>正版查询</title>
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
<script>
function checkURL()
{
	var url;
	url = document.auth.url.value;

	if (url.indexOf(" ")>=0){
		url = url.replace(/ /g,"");
		document.auth.url.value = url;
	}
	if (url.toLowerCase().indexOf("http://")==0){
		url = url.slice(7);
		document.auth.url.value = url;
	}
	if (url.toLowerCase().indexOf("https://")==0){
		url = url.slice(8);
		document.auth.url.value = url;
	}
	if (url.slice(url.length-1)=="/"){
		url = url.slice(0,url.length-1);
		document.auth.url.value = url;
	}
}
</script>
<div class="container">    <div class="header">
        <ul class="nav nav-pills pull-right" role="tablist">
          <li role="presentation" class="active"><a href="index.php">正版查询</a></li>
          <li role="presentation"><a href="./dlcx.php">授权商QQ查询</a></li>
          <li role="presentation"><a href="./kmsq.php">卡密授权</a></li>
          <li role="presentation"><a href="./get">下载程序</a></li>
        </ul>
        <h3 class="text-muted" align="left">正版查询</h3>
     </div><hr>
	 <h3 class="form-signin-heading">输入域名查询</h3>
	 <form action="?" class="form-sign" method="get" name="auth" onSubmit="return checkURL();">
	 (不要带http://)
	 <input type="text" class="form-control" name="url" value=""><br>
	 <input type="Submit" class="btn btn-primary btn-block" name="Submit" value="点击查询"><br/>
<?php
if($url=$_GET['url']) {
	echo '<label>查询域名：</label>'.$url.'<br>';
	if(checkauth2($url)) {
		echo '<div class="alert alert-success"><img src="static/ico_success.png">查询结果：正版授权！</div>';
	}else{
		echo '<div class="alert alert-danger"><img src="static/ico_tip.png">查询结果：该网站未授权！</div>';
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
<p style="text-align:center"><br>Powered by <a href="https://jq.qq.com/?_wv=1027&k=5oQjFG2">白云&小杰</a>!</p></div>
<!--百度提交 QWQ_结束 -->
<script>
			(function(){
				var bp = document.createElement('script');
				var curProtocol = window.location.protocol.split(':')[0];
				if (curProtocol === 'https') {
					bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';        
				}
				else {
					bp.src = 'http://push.zhanzhang.baidu.com/push.js';
				}
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(bp, s);
			})();
</script>
<!--百度提交 QWQ_开始 -->
</body>
</html>