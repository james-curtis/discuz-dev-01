<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


	list($ec_contract, $ec_securitycode, $ec_partner, $ec_creditdirectpay) = explode("\t", authcode($_G['setting']['ec_contract'], 'DECODE', $_G['config']['security']['authkey']));
/*
	echo $ec_contract;
	echo "<br/>";
	echo $ec_securitycode;
	echo "<br/>";
	echo $ec_partner;
	echo "<br/>";
	echo $ec_creditdirectpay;
*/
//判断是否使用纯即时到帐接口。
define('CJxixi_DIRECTPAY', $ec_creditdirectpay);
//定义常量
define('CJxixi_PARTNER',$ec_partner);
define('CJxixi_SECURITYCODE',$ec_securitycode);

//echo "ec_creditdirectpay=".$ec_creditdirectpay;


	$alipayargs = array(
		'subject' 		=> $_G['setting']['bbname'].' - '.$_G['member']['username'].' - ',
		//'body' 			=> '20131231Cjxixi',
		'body' 			=> lang('plugin/ymg6_vip', 'order_group_name').'['.' '.$gname.'] ('.$_G['clientip'].')',
		'service' 		=> 'trade_create_by_buyer',
		'partner' 		=> CJxixi_PARTNER,
		'notify_url' 		=> $_G['siteurl'].'source/plugin/ymg6_vip/returnnotify_url.php',
		'return_url' 		=> $_G['siteurl'].'source/plugin/ymg6_vip/returnnotify_url.php',
		'show_url'		=> $_G['siteurl'].'plugin.php?id=ymg6_vip',
		'_input_charset' 	=> CHARSET,
		'out_trade_no' 		=> $orderid,
		'price' 		=> 0.01,
		'quantity' 		=> 1,
		'seller_email' 		=> $_G['setting']['ec_account'],
		'extend_param'	=> 'isv^dz11',
	//	'logistics_type' =>'EXPRESS',
	//	'logistics_fee'=>0,
	//	'logistics_payment'=>'SELLER_PAY',
		'payment_type'=>1,
	);
	if(CJxixi_DIRECTPAY) {
		$alipayargs['service'] = 'create_direct_pay_by_user';
	//	$args['payment_type'] = '1';
	} else {
		$alipayargs['logistics_type'] = 'EXPRESS';
		$alipayargs['logistics_fee'] = 0;
		$alipayargs['logistics_payment'] = 'SELLER_PAY';
		//$args['payment_type'] = 1;
	}
	
	
		function getalipayargs($_G,$extcreditstitle){
			
					$orderid  = dgmdate(TIMESTAMP, 'YmdHis').random(20);
					
					$alipayargs = array(
					'subject' 		=>$_G['member']['username'].' - '.$extcreditstitle,
				//	'body' 			=> '20131231Cjxixi',
					'body' 			=> $extcreditstitle."  ".$_G['clientip'],
					'service' 		=> 'trade_create_by_buyer',
					'partner' 		=> CJxixi_PARTNER,
					'notify_url' 		=> $_G['siteurl'].'source/plugin/ymg6_vip/returnnotify_url.php',
					'return_url' 		=> $_G['siteurl'].'source/plugin/ymg6_vip/returnnotify_url.php',
					'show_url'		=> $_G['siteurl'].'plugin.php?id=ymg6_vip',
					'_input_charset' 	=> CHARSET,
					'out_trade_no' 		=> $orderid,
					'price' 		=> 0.01,
					'quantity' 		=> 1,
					'seller_email' 		=> $_G['setting']['ec_account'],
					'extend_param'	=> 'isv^dz11',
				//	'logistics_type' =>'EXPRESS',
				//	'logistics_fee'=>0,
				//	'logistics_payment'=>'SELLER_PAY',
					'payment_type'=>1,
		);
			if(CJxixi_DIRECTPAY) {
				$alipayargs['service'] = 'create_direct_pay_by_user';
			//	$args['payment_type'] = '1';
			} else {
				$alipayargs['logistics_type'] = 'EXPRESS';
				$alipayargs['logistics_fee'] = 0;
				$alipayargs['logistics_payment'] = 'SELLER_PAY';
				//$args['payment_type'] = 1;
			}
			return $alipayargs;
		}
//	$alipayurl = Parsealipayargs($alipayargs,$ec_securitycode);
//	echo $alipayurl;
	function Parsealipayargs($alipayargs,$ec_securitycode)
	{
		ksort($alipayargs);
		//var_dump($alipayargs);
		//return;
		$urlstr = $sign = '';
		foreach($alipayargs as $key => $val) {
			$sign .= '&'.$key.'='.$val;
			$urlstr .= $key.'='.rawurlencode($val).'&';
		}
		$sign = substr($sign, 1);
		$sign = md5($sign.$ec_securitycode);
		return 'https://www.alipay.com/cooperate/gateway.do?'.$urlstr.'sign='.$sign.'&sign_type=MD5';
	}
	
	//返回结果验证
	function verifyNotify()
	{
			global $_G;
			if(!empty($_POST)) {
					$notify = $_POST;
					$location = FALSE;
			} elseif(!empty($_GET)) {
					$notify = $_GET;
					$location = TRUE;
			} else {
					exit('Access Denied');
			}
			if(dfsockopen("http://notify.alipay.com/trade/notify_query.do?partner=".CJxixi_PARTNER."&notify_id=".$notify['notify_id'], 60) !== 'true')
			 {
				exit('Access Denied');
			}
			if(!CJxixi_SECURITYCODE) {
				exit('Access Denied');
			}
			ksort($notify);
			$sign = '';
			foreach($notify as $key => $val) {
				if($key != 'sign' && $key != 'sign_type') 
						$sign .= "&$key=$val";
			}
			if($notify['sign'] != md5(substr($sign,1).CJxixi_SECURITYCODE)) {
				exit('Access Denied');
			}
		
//	if((!CJxixi_DIRECTPAY && $notify['notify_type'] == 'trade_status_sync' && ($notify['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $notify['trade_status'] == 'TRADE_FINISHED') || CJxixi_DIRECTPAY && ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS'))
//		|| $type == 'trade' && $notify['notify_type'] == 'trade_status_sync') {

if((!CJxixi_DIRECTPAY && $notify['notify_type'] == 'trade_status_sync' && ( $notify['trade_status'] == 'TRADE_FINISHED') || CJxixi_DIRECTPAY && ($notify['trade_status'] == 'TRADE_FINISHED' || $notify['trade_status'] == 'TRADE_SUCCESS'))
	|| $type == 'trade' && $notify['notify_type'] == 'trade_status_sync') {
		return array(
			'validatorResult'	=> TRUE,
			'order_status' 	=>  $notify['trade_status'],
			'order_no' 	=> $notify['out_trade_no'],
			'order_price' 	=> !CJxixi_DIRECTPAY && $notify['price'] ? $notify['price'] : $notify['total_fee'],
			'trade_no'	=> $notify['trade_no'],
			'notify'	=> 'success',
			'location'	=> $location
			);
	} else {
		return array(
			'validator'	=> FALSE,
			'notify'	=> 'fail',
			'location'	=> $location
			);
	}
			
			
		
	}
	?>