<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$type = intval($_GET['type']);
if(!in_array($type, array(1,2)))  $type = 1;
$types = C::t("#ticket#types")->fetch_all();
$newtypes = array();
foreach ($types as $k => $v) {
	$newtypes[$v['t_id']] = $v;
}
$types = $newtypes;
$perpage = 20;
$page = intval ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$start = ($page - 1) * $perpage;
if ($start < 0) $start = 0;
if($type == 1){
	$count = C::t("#ticket#main")->count_notclose($_G['uid']);
	$list = C::t("#ticket#main")->fetch_all_notclose($_G['uid'],$start,$perpage);
}else{
	$count = C::t("#ticket#main")->count_close($_G['uid']);
	$list = C::t("#ticket#main")->fetch_all_close($_G['uid'],$start,$perpage);
}
$multi=	multi($count, $perpage, $page, "plugin.php?id=ticket&mod=list&type=".$type );
?>