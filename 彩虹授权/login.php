<?php
//php防注入和XSS攻击通用过滤. 
$_GET     && SafeFilter($_GET);
$_POST    && SafeFilter($_POST);
$_COOKIE  && SafeFilter($_COOKIE);
  
function SafeFilter (&$arr) 
{
     
   $ra=Array('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/','/script/','/javascript/','/vbscript/','/expression/','/applet/','/meta/','/xml/','/blink/','/link/','/style/','/embed/','/object/','/frame/','/layer/','/title/','/bgsound/','/base/','/onload/','/onunload/','/onchange/','/onsubmit/','/onreset/','/onselect/','/onblur/','/onfocus/','/onabort/','/onkeydown/','/onkeypress/','/onkeyup/','/onclick/','/ondblclick/','/onmousedown/','/onmousemove/','/onmouseout/','/onmouseover/','/onmouseup/','/onunload/');
     
   if (is_array($arr))
   {
     foreach ($arr as $key => $value) 
     {
        if (!is_array($value))
        {
          if (!get_magic_quotes_gpc())             //不对magic_quotes_gpc转义过的字符使用addslashes(),避免双重转义。
          {
             $value  = addslashes($value);           //给单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）加上反斜线转义
          }
          $value       = preg_replace($ra,'',$value);     //删除非打印字符，粗暴式过滤xss可疑字符串
          $arr[$key]     = htmlentities(strip_tags($value)); //去除 HTML 和 PHP 标记并转换为 HTML 实体
        }
        else
        {
          SafeFilter($arr[$key]);
        }
     }
   }
}
?>
<?php
//查询禁止IP
$ip =$_SERVER['REMOTE_ADDR'];
$fileht=".htaccess2";
if(!file_exists($fileht))file_put_contents($fileht,"");
$filehtarr=@file($fileht);
if(in_array($ip."\r\n",$filehtarr))die("警告:"."<br>"."您的IP地址被某些原因禁止，如果您有任何问题请发邮件至760611885qq.com！");
  
//加入禁止IP
$time=time();
$fileforbid="log/forbidchk.dat";
if(file_exists($fileforbid))
{ if($time-filemtime($fileforbid)>60)unlink($fileforbid);
else{
$fileforbidarr=@file($fileforbid);
if($ip==substr($fileforbidarr[0],0,strlen($ip)))
{
if($time-substr($fileforbidarr[1],0,strlen($time))>600)unlink($fileforbid);
elseif($fileforbidarr[2]>600){file_put_contents($fileht,$ip."\r\n",FILE_APPEND);unlink($fileforbid);}
else{$fileforbidarr[2]++;file_put_contents($fileforbid,$fileforbidarr);}
}
}
}
//防刷新
$str="";
$file="log/ipdate.dat";
if(!file_exists("log")&&!is_dir("log"))mkdir("log",0777);
if(!file_exists($file))file_put_contents($file,"");
$allowTime = 30;//防刷新时间
$allowNum=10;//防刷新次数
$uri=$_SERVER['REQUEST_URI'];
$checkip=md5($ip);
$checkuri=md5($uri);
$yesno=true;
$ipdate=@file($file);
foreach($ipdate as $k=>$v)
{ $iptem=substr($v,0,32);
$uritem=substr($v,32,32);
$timetem=substr($v,64,10);
$numtem=substr($v,74);
if($time-$timetem<$allowTime){
if($iptem!=$checkip)$str.=$v;
else{
$yesno=false;
if($uritem!=$checkuri)$str.=$iptem.$checkuri.$time."1\r\n";
elseif($numtem<$allowNum)$str.=$iptem.$uritem.$timetem.($numtem+1)."\r\n";
else
{
if(!file_exists($fileforbid)){$addforbidarr=array($ip."\r\n",time()."\r\n",1);file_put_contents($fileforbid,$addforbidarr);}
file_put_contents("log/forbided_ip.log",$ip."--".date("Y-m-d H:i:s",time())."--".$uri."\r\n",FILE_APPEND);
$timepass=$timetem+$allowTime-$time;
die("提示:"."<br>"."您的刷新频率过快，请等待 ".$timepass." 秒后继续使用!");
}
}
}
}
if($yesno) $str.=$checkip.$checkuri.$time."1\r\n";
file_put_contents($file,$str);
?>
<?php
/**
 * 登录
**/
$mod='blank';
include("./api.inc.php");
if(isset($_POST['user']) && isset($_POST['pass'])){
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$code=daddslashes($_POST['code']);
	$row = $DB->get_row("SELECT * FROM auth_user WHERE user='$user' limit 1");
	if($row['user']=='') {
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('登陆失败');history.go(-1);</script>");
		}elseif(!$code || strtolower($_SESSION['tgyd_code'])!=strtolower($code)){
		exit("<script language='javascript'>alert('验证码错误');history.go(-1);</script>");
	}elseif ($pass != $row['pass']) {
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('用户名或密码不正确！');history.go(-1);</script>");
	}elseif($row['user']==$user && $row['pass']==$pass){
		//get ips
		$ips = explode(',',$row['ips']);
		//if 
		if ($row['ips'] != '0'){
			if (count($ips) > 0 && is_array($ips) && $row['ips'] != ""){
				if (!in_array($clientip,$ips))
				{
					exit("<script language='javascript'>alert('不要异地登陆哦');history.go(-1);</script>");
				}
			} else { //不存在时默认记录当前ip
				$DB->query("UPDATE `auth_user` SET `ips`='{$clientip}' WHERE `uid`='{$row['uid']}' LIMIT 1");
			}
		}
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
		setcookie("auth_token", $token, time() + 604800);
		@header('Content-Type: text/html; charset=UTF-8');
		$city=get_ip_city($clientip);
		$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$user."','登陆平台','".$date."','".$city."','IP:".$clientip."')");
		exit("<script language='javascript'>alert('登陆授权平台成功！');window.location.href='./admin';</script>");
	}
}elseif(isset($_GET['logout'])){
	setcookie("auth_token", "", time() - 604800);
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($islogin==1){
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./admin';</script>");
}
$title='用户登录';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$title?></title>
<meta name="keywords" content="授权平台,授权平台"/>
<meta name="description" content="授权平台授权平台"/>
<link rel="stylesheet" href="/assets/Public/Style/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="/assets/Public/wap/css/wap.css" type="text/css" />
<link rel="stylesheet" href="/assets/Public/wap/css/app.css" type="text/css" />
</head>
<body class="bg-white" ontouchstart="">
<div class="container w-xxxl padder">
<div class="text-center logo">
<img src="//q4.qlogo.cn/headimg_dl?dst_uin=760611885&spec=100" height="106px" class="b b-3x">
</div>
<form action="./login.php" method="post" class="form-horizontal" role="form">
<div class="list b-t-0 m-t padder-0">
<div class="input-group">
<span class="input-group-addon padder-0">账号</span>
<input type="text" name="user" class="form-control no-border"  placeholder="用户名" required="required" >
</div>
</div>
<div class="list b-t-0 m-t padder-0">
<div class="input-group">
<span class="input-group-addon padder-0">密码</span>
<input type="password" class="form-control no-border" name="pass" placeholder="密码" required="required" >
</div>
</div>
<div class="list b-t-0 m-t padder-0">
<div class="input-group">
<span class="input-group-addon padder-l-0">验证码</span>
<input type="text" name="code" maxlength="5" class="form-control no-border padder-0" placeholder="输入验证码" onkeydown="if(event.keyCode==32){return false;}" required>
<span class="input-group-btn padder-0">
<img src="/assets/Public/code.php?+Math.random();" onclick="this.src='/assets/Public/code.php?'+Math.random();" title="点击更换验证码" style="margin-bottom:5px;border: 1px solid #5CAFDE;">
</div>
</div>
<div class="m-t-lg">
<label class="checkbox i-checks">
<input type="checkbox" id="remember" value="1"><i></i>
记住我 <small> (在公共设备登陆时请不要勾选)</small>
</label>
</div>
<button class="btn btn-lg btn-info btn-block m-t-xl" type="submit" style="width:100%;height:100%;">登��录</button>
</div>
</div>
<script src="/assets/Public/Style/js/jquery-2.1.1.min.js"></script>
<script src="/assets/Public/wap/layer_mobile/layer.js"></script>
<script src="/assets/Public/wap/js/app.js"></script><script type="text/javascript"> $(document).keyup(function(event){ if(event.keyCode ==13){ login(''); } }); </script>
</body>
</html>