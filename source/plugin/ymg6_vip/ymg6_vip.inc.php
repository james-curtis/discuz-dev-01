<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


//	require_once DISCUZ_ROOT.'/source/plugin/ymg6_vip/alipayapi.php';
/*require_once DISCUZ_ROOT.'/source/plugin/ymg6_vip/returnnotify_url.php';

return;*/

/*for($i=0;$i<=100;$i++){
	$orderid =  random(20);
	echo $orderid;
	echo "<br/>";
}

return;*/


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'/source/plugin/ymg6_vip/alipayhandler.php';

//var_dump($_G);

/*	$PHP_SELFTmp = $_SERVER['PHP_SELF'];
	$siteurl = dhtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].preg_replace("/\/+(source\/plugin\/qmx8_buy_usergroup)?\/*$/i", '', substr($PHP_SELFTmp, 0, strrpos($PHP_SELFTmp, '/'))).'/');
	echo $siteurl;
	return;*/


/*var_dump($_G);
return;*/

/*		$aa  =  C::t('#ymg6_vip#ymg6_vip')->fetch_buy_order_id('20140108120825Sq6H4xKlLh4LuZl9p016');
		$test=  C::t('#ymg6_vip#ymg6_vip')->update_by_order_id('20140108132847gqV8e33v13XFC72DDwXF','123456',$_G[timestamp] );
		var_dump($test);
		return;*/
		
/*		$test = "extcredits1:100,extcredits2:100";
		$tmp=explode(',', $test);
			var_dump($tmp);
		foreach ($tmp as $value) {

					$credit=explode(':', $value);
				 var_dump($credit);
		}
		
		return;*/


 	//$requesturl = Parsealipayargs($alipayargs,$ec_securitycode);
 	//return;

	/*
	
	echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">payform.submit();</script>';
	return;*/
	
	

	
	//echo $requesturl;

$setContent =  $_G['cache']['plugin']['ymg6_vip'];
//var_dump($setContent);
$cjxixi_rule = handlercjxixi_rule(explode("\r\n",$setContent['cjxixi_rule']));
//var_dump($cjxixi_rule);
$cjxixi_note =explode("\r\n",$setContent['cjxixi_note']);
$cjxixi_isshow = $setContent['cjxixi_isshow'];
//var_dump($cjxixi_isshow);

$myname = $_G['username'];
$uid = $_G['uid'];
$grouptitle = $_G['group']['grouptitle'];
$myweiwang = getuserprofile(extcredits1);
$mymoey = getuserprofile(extcredits2);
$mygongxian = getuserprofile(extcredits3);
$moneyname = $_G['setting']['extcredits']['2']['title'];
$weiwangname = $_G['setting']['extcredits']['1']['title'];
$gongxianname = $_G['setting']['extcredits']['3']['title'];
/*$islogin = 1;
if(!$uid){
	$islogin = 0;
}
*/
if($cjxixi_isshow ==1)
{
	$order_completed_data = 	C::t('#ymg6_vip#ymg6_vip')->fetch_ordercompleted_data();

	$ocdCount = count($order_completed_data);
	for($i=0;$i<$ocdCount;$i++){
		//$order_completed_data[$i]['confirmdate']  = date('Y-m-d H:i:s',$order_completed_data[$i]['confirmdate'] );
	//test
	$order_completed_data[$i]['submitdate']  = date('Y-m-d',$order_completed_data[$i]['submitdate'] );
}

/*	var_dump($order_completed_data);
	echo $order_completed_data['confirmdate'];
	$dfd = date('Y-m-d',$order_completed_data['confirmdate'] );
	echo $dfd;*/
}


if($_GET['action'] == 'buytrue'){
	
	// 1/4 
	if(!$_G['uid']) {
		showmessage('not_loggedin', NULL, array(), array('login' => 1));
	}

	//return;
	if(!$_G['setting']['ec_contract']){
		showmessage(lang('plugin/ymg6_vip', 'eccontractinfo'), NULL, array(), array());
		return;
	
	}
	
	$buyIdKey = $_GET['buyId'] ;	
	include template('common/header_ajax');
	include template('ymg6_vip:go_pay_money');
	include template('common/footer_ajax');
}

if($_POST['action'] == 'writeData')
{
	if(submitcheck('action')){
		//	$c = $_GET['IDKey'];
			//$buyIdKey = $_POST['IDKey'] ;
		}else{
			echo "args error";
			return;
		}
	require_once DISCUZ_ROOT.'/source/plugin/ymg6_vip/alipayapi.php';
	$buyIdKey = $_POST['IDKey'] ;
	if(!isset($cjxixi_rule[$buyIdKey]))
	{
		echo $buyIdKey;
		exit('Access Denied');
	}
	echo lang('plugin/ymg6_vip', 'skip_info');
	$titleStr ='';
	if($cjxixi_rule[$buyIdKey][3] ==0){
			$titleStr = lang('plugin/ymg6_vip', 'order_group_name').$cjxixi_rule[$buyIdKey][0].lang('plugin/ymg6_vip', 'validityinfo');
	}else{
		$titleStr = lang('plugin/ymg6_vip', 'order_group_name').$cjxixi_rule[$buyIdKey][0].$cjxixi_rule[$buyIdKey][3]."_Day";
	}


	$alipayargsstr = getalipayargs($_G,$titleStr);
	
	$alipayargsstr['price' ] = floatval ($cjxixi_rule[$buyIdKey][2]);
	 
	 
	$insertdata = array(
			'order_id' => $alipayargsstr['out_trade_no'],
			'order_status' =>1,
			'uid' =>$uid,
			'username'=>$myname,
			'trade_no'=>'',
			'group_id'=>$cjxixi_rule[$buyIdKey][1],
			'group_name'=>$cjxixi_rule[$buyIdKey][0],
			'extcreditstitle'=>$cjxixi_rule[$buyIdKey][4],
			'extcredits'=>$cjxixi_rule[$buyIdKey][5],
			'price'=>$cjxixi_rule[$buyIdKey][2],
			'validity'=>$cjxixi_rule[$buyIdKey][3],
			'submitdate'=>time(),
			'confirmdate'=>0,
			'ip'=>$_G['clientip'],
			'pay_type'=>'alipay',
	);			
	C::t('#ymg6_vip#ymg6_vip')->insert_data($insertdata);
	

	$requesturl = Parsealipayargs($alipayargsstr,$ec_securitycode);
	echo '<form id="aplipaysubmit" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">aplipaysubmit.submit();</script>';
		
}

include template('ymg6_vip:index');


	//include template('common/header_ajax');
//echo '<form id="payform" action="'.$requesturl.'" method="post"></form><script type="text/javascript" reload="1">payform.submit();</script>';
//	include template('common/footer_ajax');

?>