<?php
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
<head>
<meta name="baidu-site-verification" content="hE6VuDTDDZ" />
<meta name='zyiis_check_verify' content='8522ccc657813a5aa350dbc60e15ac37'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
<meta name="format-detection" content="telephone=no">
<meta charset="UTF-8">
<meta name="description" content="Violate Responsive Admin Template">
<meta name="keywords" content="Super Admin, Admin, Template, Bootstrap">
<title>卡密授权</title>
<link href="static/css/music.css" rel="stylesheet">
<link href="static/css/bootstrap.min.css" rel="stylesheet">
<link href="static/css/animate.min.css" rel="stylesheet">
<link href="static/css/font-awesome.min.css" rel="stylesheet">
<link href="static/css/form.css" rel="stylesheet">
<link href="static/css/calendar.css" rel="stylesheet">
<link href="static/css/file-manager.css" rel="stylesheet">
<link href="static/css/style.css" rel="stylesheet">
<link href="static/css/icons.css" rel="stylesheet">
<link href="static/css/generics.css" rel="stylesheet">
<STYLE>.loginscreen.middle-box{width:300px;}.middle-box{max-width:400px;z-index:100;margin:0 auto;padding-top:100px;}</STYLE>
</head>
<body id="skin-blur-violate">
<section class="middle-box " align="center">
<div class="tile p-15">
<div class="container">
<img id="btnAudio" class="profile-pic animated" src="https://q.qlogo.cn/headimg_dl?bs=qq&dst_uin=760611885&fid=blog&spec=100" alt="">
<form class="form-horizontal" method="post" action="">
<input type="hidden" name="do" value="do">
<div class="form-group">
<label for="exampleInputEmail1">自助授权</label>
<div class="panel-body">
<label for="exampleInputEmail1">卡密</label>
 <input type="text" name="km" class="form-control" placeholder="请输入卡密!">
 <label for="exampleInputEmail1">域名</label>
 <input type="text" name='url' class="form-control" placeholder="请输入域名(不加http://)">
 <label for="exampleInputEmail1">QQ</label>
 <input type="text" name='qq' class="form-control" placeholder="请输入QQ!">
 <label for="exampleInputEmail1">注册账号</label>
 <input type="text" name='user' class="form-control" placeholder="请输入账号(可以随意输入)">
 <label for="exampleInputEmail1">注册密码</label>
 <input type="text" name='pass' class="form-control" placeholder="请输入密码(可以随意输入)">
</div>
<div class="form-group">
</label>
</div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<button type="submit" class="btn btn-block btn-alt" >确认授权</button>
</div>
<a data-toggle="modal" href="javascript:history.back(-1)" class="btn btn-sm m-t-10">返回上一页</a>
<a data-toggle="modal" href="/kmsq.php" class="btn btn-sm m-t-10">卡密授权</a>
<a href="./dlcx.php" class="btn btn-sm m-t-10">授权商查询</a>
<a href="./pay.php" target="_blank" class="btn btn-sm m-t-10">购买授权</a>
</form>
</div>
<script src="static/js/jquery.min.js"></script>  
<script src="static/js/jquery-ui.min.js"></script>  
<script src="static/js/bootstrap.min.js"></script>
<script src="static/js/scroll.min.js"></script>  
<script src="static/js/calendar.min.js"></script>  
<script src="static/js/feeds.min.js"></script>  
<script src="static/js/elfinder.min.js"></script>  
<script src="static/js/music.js"></script>
</body>
</html>