<?php
error_reporting(0);
define('IN_CRONLITE', true);
define('CACHE_FILE', 0);
define('SYSTEM_ROOT', dirname(__FILE__).'/');
define('ROOT', dirname(SYSTEM_ROOT).'/');
date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';

require ROOT.'config.php';
//连接数据库
include_once(ROOT."includes/db.class.php");
$DB=new DB($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port']);

include ROOT.'includes/cache.class.php';
$CACHE=new CACHE();
$conf=unserialize($CACHE->read());

include ROOT.'includes/authcode.php';
define('authcode',$authcode);
include ROOT.'includes/function.php';
include ROOT.'includes/core.func.php';

$clientip=real_ip();

if($conf['payapi']==1){
	$payapi = 'http://www.ufun.me/';
}elseif($conf['payapi']==2){
	$payapi = 'https://pay.blyzf.cn/';
}elseif($conf['payapi']==3){
	$payapi = 'http://pay.blpay.me/';
}elseif($conf['payapi']==4){
	$payapi = 'http://tx87.cn/';
}elseif($conf['payapi']==5){
	$payapi = 'http://pay.canxue.me/';
}elseif($conf['payapi']==6){
	$payapi = 'http://pay.hackwl.cn/';
}elseif($conf['payapi']==7){
	$payapi = 'http://pay.weigj.org/';
}elseif($conf['payapi']==8){
	$payapi = 'http://www.jiuaipay.com/';
}elseif($conf['payapi']==9){
	$payapi = 'http://pay.187ka.com/';
}elseif($conf['payapi']==10){
	$payapi = 'https://pay.gfvps.cn/';
}elseif($conf['payapi']==11){
	$payapi = 'http://pay.koock.cn/';
}elseif($conf['payapi']==12){
	$payapi = 'http://www.o10086.cn/';
}else{
	$payapi = 'https://pay.xr876.cn/';
}

function showalert($msg,$status,$orderid=null,$tid=0){
	if($tid==-1)$link = '../user/';
	elseif($tid==-2)$link = '../user/regok.php?orderid='.$orderid;
	else $link = '../index.php';
	echo '<meta charset="utf-8"/><script>alert("'.$msg.'");window.location.href="'.$link.'";</script>';
}