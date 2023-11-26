<?php
$install = true;
require_once('../includes/common.php');
@header('Content-Type: text/html; charset=UTF-8');
if($conf['version']<1033){
	$sqls = file_get_contents('update3.sql');
	$version = 1033;
}elseif($conf['version']<1036){
	$sqls = file_get_contents('update4.sql');
	$version = 1036;
}else{
	$sqls = file_get_contents('update5.sql');
	$version = 1039;
}
$explode = explode(';', $sqls);
$num = count($explode);
foreach ($explode as $sql) {
    if ($sql = trim($sql)) {
        $DB->query($sql);
    }
}
saveSetting('version',$version);
$CACHE->clear();
exit("<script language='javascript'>alert('网站数据库升级完成！');window.location.href='../';</script>");
?>
