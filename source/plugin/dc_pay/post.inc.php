<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if($_GET['act']=='paycheck'){
	$orderid = trim($_GET['orderid']);
	$order = C::t('#dc_pay#dc_pay_order')->getbyorderid($orderid);
	include template('common/header_ajax');
	if($order['status']==1){
		echo '1';
	}
	include template('common/footer_ajax');
	dexit();
}
$data = trim($_GET['data']);
$chk = trim($_GET['chk']);
if(substr(md5($data.FORMHASH),0,8)!=$chk)showmessage('dc_pay:error');
list($paytype,$orderid,$ismobile,$time) = explode('|',$data);
$order = C::t('#dc_pay#dc_pay_order')->getbyorderid($orderid);
if(empty($order)||$order['status'])showmessage('dc_pay:error');
$paytypes = @include DISCUZ_ROOT.'/source/plugin/dc_pay/data/config.php';
foreach($paytypes as $k => $p){
	if(!$ismobile){
		if($p['enable'])$_paytypes[$k]=$p;
	}else{
		if($p['mobile']&&$p['mobileenable'])$_paytypes[$k]=$p;
	}
}
if(empty($_paytypes[$paytype]))showmessage('dc_pay:error');
C::import('api/pay','plugin/dc_pay',false);
C::import($paytype.'/pay','plugin/dc_pay/api',false);
$modstr = $ismobile?$paytype.'_mobilepay':$paytype.'_pay';
if (!class_exists($modstr,false))showmessage('dc_pay:error');
$payobj = new $modstr();
$payobj->setorder($order['orderid'], $order['price'], $order['subject'],'','');
$payurl = $payobj->create_payurl();
if(empty($payurl))showmessage('dc_pay:error');
if(is_array($payurl)){
	if($payurl['type']!='qrcode'){
		$sHtml = "<html><head></head><body><form id='".$paytype."submit' name='".$paytype."submit' action='".$payurl['gateway']."' method='post'>";
		foreach($payurl['args'] as $key => $val) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
		$sHtml = $sHtml."<input type='submit' value='ok' style='display:none;'></form>";
		$sHtml = $sHtml."<script>document.forms['".$paytype."submit'].submit();</script></body></html>";
		echo $sHtml;
		die();
	}else{
		$pt = $_paytypes[$paytype]['alias']?$_paytypes[$paytype]['alias']:$_paytypes[$paytype]['title'];
		include template('dc_pay:qrcode');
	}
}else{
    if((stripos($payurl,'http://') !== false || stripos($payurl,'https://') !== false) && !(stripos($payurl,'<script>') !== false)) {
        header('location:'.$payurl);
    } else {
        echo $payurl;
    }

}
?>