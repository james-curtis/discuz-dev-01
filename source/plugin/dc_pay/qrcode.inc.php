<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$data = trim($_GET['data']);
if($_GET['formhash']!=FORMHASH)die();
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/qrcode.class.php';
QRcode::png($data,false,QR_ECLEVEL_L,8,3);
?>