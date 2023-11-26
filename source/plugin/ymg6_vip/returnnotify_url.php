<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

//	echo "success";
	
	define('IN_API', true);
	define('CURSCRIPT', 'api');
	require '../../../source/class/class_core.php';
	$discuz = C::app();
	$discuz->init();
	
	$PHP_SELFTmp = $_SERVER['PHP_SELF'];
	$_G['siteurl'] = dhtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].preg_replace("/\/+(source\/plugin\/ymg6_vip)?\/*$/i", '', substr($PHP_SELFTmp, 0, strrpos($PHP_SELFTmp, '/'))).'/');
	//echo $siteurl;

	require_once DISCUZ_ROOT.'/source/plugin/ymg6_vip/alipayapi.php';
	
	
	//var_dump($_G);
	$verifyResult = verifyNotify();
	
	if($verifyResult['validatorResult'])
	{
		
		$orderID = $verifyResult['order_no'];
		$orderinfo = C::t('#ymg6_vip#ymg6_vip')->fetch_buy_order_id($orderID);
		$order_price= $verifyResult['order_price'];
/*		var_dump($orderID);
		var_dump($orderinfo);
		var_dump($order_price);
		return;*/
		
		if($orderinfo  &&  (floatval($order_price) == $orderinfo['price']))
		{
					if($orderinfo['order_status'] == 1)
					 {
					 	
					
						
						$member=C::t('common_member')->fetch($orderinfo['uid'], false, 1);
						
						if(count($member)==0){
									$isinarchive = '_inarchive';
						}else{
							    	$isinarchive = '';
						}
						
						C::t('#ymg6_vip#ymg6_vip')->update_by_order_id($orderID,$verifyResult['trade_no'],$_G[timestamp] );
						
						//设置群组的有效日期
						if($orderinfo['validity']==0){
	
								C::t('common_member'.$isinarchive)->update($orderinfo['uid'], array('groupid'=>$orderinfo['group_id']));
								
						}elseif (is_numeric($orderinfo['validity']) && $orderinfo['validity']>0) {
							
								$groupexpiryTime =strtotime(date('Y-m-d H:i:s',strtotime("+$orderinfo[validity] day")));
								
								
							//	echo $orderinfo['group_id']."-----".$groupexpiryTime;
							
								C::t('common_member'.$isinarchive)->update($orderinfo['uid'], array('groupid'=>$orderinfo['group_id'],'groupexpiry'=>$groupexpiryTime));
								
								$groupterms['main'] = array('time' => $groupexpiryTime);
								$groupterms['ext'][$orderinfo['group_id']] = $groupexpiryTime;
								
								C::t('common_member_field_forum'.$isinarchive)->update($orderinfo['uid'],array('groupterms' => serialize($groupterms)));
					  }
					  
					  //根据规则送积分
					  if($orderinfo['extcredits']!="" && $orderinfo['extcredits']!="0"){
								$tmpExt=explode(',', $orderinfo['extcredits']);
								
								foreach ($tmpExt as $value) {
									
										$extcredits=explode(':', $value);
										updatemembercount($orderinfo['uid'], array($extcredits[0] =>$extcredits[1]));
								
								}
						}	
							//处理跳转链接：
		
							
					}
	
		}

						showmessage('ymg6_vip:pay_sucess', $_G['siteurl'].'home.php?mod=spacecp&ac=usergroup');
	}else
	{
						showmessage('购买失败,请联系管理员(提示：请确认您选择了正确的交易方式)', $_G['siteurl']);
	}
	
	//var_dump($verifyResult);
	
?>