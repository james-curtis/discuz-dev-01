<?php
include("api.inc.php");

$url = isset($_GET['url'])?daddslashes($_GET['url']):daddslashes($_GET['url']);
$authcode = isset($_GET['authcode'])?daddslashes($_GET['authcode']):daddslashes($_GET['authcode']);

if(!$url or !$authcode){
	exit('no');
}

if($DB->get_row("SELECT * FROM  `auth_block` where url ='$url'")){
	exit('url_existence'); //存在
}

if($row_sqdata = $DB->get_row("SELECT * FROM  `auth_site` where url ='$url'")){//判断是否授权
	if(!$DB->get_row("SELECT * FROM  `auth_site` where authcode ='$authcode'")){
		if($DB->query("INSERT INTO  `auth_block` (`url`, `authcode`, `date`) VALUES ('$url', '1', '$date')")){
			exit('Already in storage_fsq'); //破解版入库
		}else{
			exit('System error');
		}
	}
}else{
	if($DB->query("INSERT INTO  `auth_block` (`url`, `authcode`, `date`) VALUES ('$url', '0', '$date')")){
		exit('Already in storage'); //未授权入库
	}else{
		exit('System error');
	}
}
?>