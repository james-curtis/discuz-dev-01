<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
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
						
						//����Ⱥ�����Ч����
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
					  
					  //���ݹ����ͻ���
					  if($orderinfo['extcredits']!="" && $orderinfo['extcredits']!="0"){
								$tmpExt=explode(',', $orderinfo['extcredits']);
								
								foreach ($tmpExt as $value) {
									
										$extcredits=explode(':', $value);
										updatemembercount($orderinfo['uid'], array($extcredits[0] =>$extcredits[1]));
								
								}
						}	
							//������ת���ӣ�
		
							
					}
	
		}

						showmessage('ymg6_vip:pay_sucess', $_G['siteurl'].'home.php?mod=spacecp&ac=usergroup');
	}else
	{
						showmessage('����ʧ��,����ϵ����Ա(��ʾ����ȷ����ѡ������ȷ�Ľ��׷�ʽ)', $_G['siteurl']);
	}
	
	//var_dump($verifyResult);
	
?>