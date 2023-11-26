<?php
include("../api.inc.php");
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$authcode=$_GET['authcode'];
$sign=$_GET['sign'];
if(!$authcode){exit();}

require_once('../pclzip.php');
$file_real=md5(substr($authcode,0,16)).'.zip';
$file='../'.CACHE_DIR."/{$file_real}";
if($_GET['my']=='installer') {
$file_path="../".PACKAGE_DIR.'/release/';
$file_str=file_get_contents('../'.PACKAGE_DIR.'/authcode.php');
$file_str=str_replace('1000000001',$authcode,$file_str);
file_put_contents('../'.PACKAGE_DIR.'/release/'.$info['authlist'],$file_str);
$file_name='release.zip';

}elseif($_GET['my']=='updater') {
$file_path="../".PACKAGE_DIR.'/update/';

//更新包
$file_str=file_get_contents("../".PACKAGE_DIR.'/authcode.php');
$file_str=str_replace('1000000001',$authcode,$file_str);
file_put_contents('../'.PACKAGE_DIR.'/update/'.$info['authlist'],$file_str);

$file_name='update.zip';

}
if(file_exists($file))unlink($file);
$zip = new PclZip($file);
$v_list = $zip->create($file_path ,PCLZIP_OPT_REMOVE_PATH,$file_path);

$file_size=filesize($file);
$content_url=$file;//下载文件地址,可以是网络地址,也可以是本地物理路径或者虚拟路径
ob_end_clean(); //函数ob_end_clean 会清除缓冲区的内容，并将缓冲区关闭，但不会输出内容。
header("Content-Type: application/force-download;"); //告诉浏览器强制下载
header("Content-Transfer-Encoding: binary"); 
header("Content-Length: {$file_size}"); 
header("Content-Disposition: attachment; filename={$file_name}"); //attachment表明不在页面输出打开，直接下载
header("Expires: 0"); 
header("Cache-control: private"); 
header("Pragma: no-cache"); //不缓存页面
readfile($content_url);
?>