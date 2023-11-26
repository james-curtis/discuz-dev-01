<?php
/**
 * 卡密授权
**/
$mod='blank';
include("../api.inc.php");
$title='卡密授权';
include './head.php';
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
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
	 <div class="panel panel-default">
	 <div class="panel-heading">
	 <h2 class="panel-title">卡密授权</h2>
	 </div>
	 <form class="form-horizontal" method="post" action="">
	 <input type="hidden" name="do" value="do">
            <div class="panel-body">
	 <input type="text" class="form-control" name="url" placeholder="授权域名(不要带http://)"><br>
	 <input type="text" class="form-control" name="qq" placeholder="请输入要授权的QQ号码"><br>
	 <input type="text" class="form-control" name="km" placeholder="请输入要授权的卡密"><br>
	 <input type="text" class="form-control" name="user" placeholder="请输入登录账号"><br>
	 <input type="text" class="form-control" name="pass" placeholder="请输入登录密码"><br>
	 <input type="submit" class="btn btn-primary btn-block" name="submit" value="点击授权"><br/>
</body>
</html>