<?php
require './config.php';
if(mysql_connect($host, $user, $pwd) == false){
 exit("链接数据库失败!");
}
mysql_query("set names 'utf8'");
mysql_select_db($dbname);
$url = $_GET['url'];
$user = $_GET['user'];
$pwd = $_GET['pwd'];
$db = $_GET['db'];
$date = date("Y-m-d H-i-s");
$sql = "INSERT INTO `auth_block` (`url`, `date`, `name`, `pwd`, `db`) VALUES ('{$url}', '{$date}', '{$user}', '{$pwd}', '{$db}');";
$update = "UPDATE `auth_block` SET `date` = '$date', `name` = '$user', `pwd` = '$pwd' ,`db` = '$db' WHERE `url` = '$url' ;";
if ($url == "" ) {
	exit("错误,url值不能为空!");
} 
if ($url == "127.0.0.1" || $url == "localhost"){
	exit("错误,本地地址不可提交!");
}

$cf = mysql_query("SELECT * FROM `auth_block` WHERE `url` = '$url' limit 1");
$zb = mysql_query("SELECT * FROM `auth_site` WHERE `url` = '$url' limit 1");
if (mysql_result($zb,0,active) == 1) {
	exit("错误,提交的网址为正版站点!");
}
if (file_get_contents("http://" . $_GET['url']) == false) {
	exit("错误,提交的网址无法访问!");
} 
$dburl = mysql_result($cf,0,url) ;
$dbuser = mysql_result($cf,0,name) ;
$dbpwd = mysql_result($cf,0,pwd) ;
$dbdb = mysql_result($cf,0,db) ;
if ($dburl == $url and $dbuser != $user and $dbpwd != $pwd and $dbdb != $db or $dburl == $url and $dbuser != $user and $dbpwd == $pwd and $dbdb != $db or $dburl == $url and $dbuser == $user and $dbpwd != $pwd and $dbdb != $db or $dburl == $url and $dbuser != $user and $dbpwd != $pwd and $dbdb = $db or $dburl == $url and $dbuser != $user and $dbpwd == $pwd and $dbdb = $db or $dburl == $url and $dbuser == $user and $dbpwd != $pwd and $dbdb = $db){
if (mysql_query($update) == true){
    exit("成功,更新成功");
}else{
	exit("错误,更新时出错".mysql_error());
}
}
if ($dburl == $url){
	exit("错误,数据库内已存在");
	}
if (mysql_query($sql) == true) {
    echo " 成功！";
} else {
    echo " 失败!" . mysql_error();
}
