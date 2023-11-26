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
include("../api.inc.php");
if(isset($_POST['user']) && isset($_POST['pass'])){
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	$row=$DB->get_row("SELECT * FROM auth_site WHERE user='$user' limit 1");
	if($user==$row['user'] && $pass==$row['pass']) {
	if($row['active']==0){
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('账号未激活，请联系管理员激活！');history.go(-1);</script>");
		}
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$user}\t{$session}", 'ENCODE', SYS_KEY);
		setcookie("user_token", $token, time() + 604800, '/');
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('登陆成功！');window.location.href='./';</script>");
	}else {
		@header('Content-Type: text/html; charset=UTF-8');
		exit("<script language='javascript'>alert('登陆失败！');history.go(-1);</script>");
	}
}elseif(isset($_GET['userlogout'])){
	setcookie("user_token", "", time() - 604800, '/');
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登陆！');window.location.href='./login.php';</script>");
}elseif($userlogin==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$title='授权代理平台登录';
?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=$title?></title>
<meta name="keywords" content="授权平台,授权平台"/>
<meta name="description" content="授权平台授权平台"/>
<!--[if lte IE 6]><script src="js/DD_belatedPNG_0.0.8a.js" type="text/javascript"></script> 
<script type="text/javascript"> DD_belatedPNG.fix('div, ul,.banner_img img,h3, a,span,dl,.market img'); </script>
<![endif]-->
<link rel="stylesheet" type="text/css"  href="./css/home.css" />
<link rel="shortcut icon" href="/favicon.ico"/>
<link rel="bookmark" href="/favicon.ico"/>
<style>
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    box-shadow:0 0 0 60px #3f8dc8 inset;
    -webkit-text-fill-color: #ffffff;
	border-radius:30px;
}

input:-webkit-autofill+label{display:none;}
</style>
</head>

<body>
	 <div class="wrapper">
     	<div class="login_area">
        	<div class="cloud"></div>
            <h1><?=$title?></h1>
            <div class="describ">授权平台管理</div>
           	<form action="./login.php" method="post" onsubmit="return check_login();" id="login_form" autocomplete="off">
            	<div class="input_area">
								
					<input type="text"  id="username" name="user" value="" autocomplete="off" /> 
					<label class="placeholder" for="username">请输入账号</label>	
				</div>
                <div class="error_tip" id="info_username"></div>
                <div class="input_area">
                	
                    <input type="password"  id="pwd" name="pass" autocomplete="off" /> 
					<label class="placeholder" for="pwd">请输入密码</label>
                	<input class="button" type="submit" id="login_btn" value="立即登录">
                </div>
					<div class="error_tip" id="info_authcode"></div>
			
				<input type="hidden" name="password" id="password"/>
        	</form>
        </div>
    	</div>
    </div>
    <div class="loading_area">
    	<img src="images/loading.gif"  id="load_icon"/>     
    </div>
    <script src="./js/jquery-1.8.0.min.js"></script>
    <script src="./js/jquery-md5-min.js"></script>
	<script src="./js/jquery.base64.js"></script>
	<script src="./js/jquery.cookie.js"></script>
    <script src="./js/login.js"></script>
    <script type="text/javascript">
	    try{
		  top.location.hostname;
		  if (top.location.hostname != window.location.hostname) {
		    top.location.href =window.location.href;
		  }
		  }catch(e){
		  top.location.href = window.location.href;
		 }
    <script>
</body>
</html>