<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

//echo lang('plugin/ymg6_vip', 'test');

//exit();

if(!submitcheck('searchsubmit')) {

//����discuz ʱ��js
echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
//showtips($templatelang['sitemap']['sitemap_tips'],"fdsfsdfsdfsdf");  
//�����ʾ��Ϣ
showtips(lang('plugin/ymg6_vip', 'tips'));
//showformheader('plugins&identifier=ymg6_vip&pmod=admincp&test=1');
//���ʼ
showtableheader(lang('plugin/ymg6_vip', 'tableheader'));
showformheader('plugins&operation=config&do='.$pluginid.'&pmod=admincp&identifier=ymg6_vip', 'testhd');  
//showtableheader();  
//showsubmit('testhd', "aaaa", "����״̬".': <br/><input name="srchusername" value="'.htmlspecialchars($_GET['srchusername']).'" class="txt" />&nbsp;&nbsp;'.$Plang['repeat'].': <input name="srchrepeat" value="'.htmlspecialchars($_GET['srchrepeat']).'" class="txt" />', $searchtext);
showsetting(lang('plugin/ymg6_vip', 'order_status'), array('order_status', array(
			array(0,lang('plugin/ymg6_vip', 'order_status_all')),
			array(1,lang('plugin/ymg6_vip', 'order_status_wait')),
			array(2, lang('plugin/ymg6_vip', 'order_status_sucess')),
		)), intval(order_status), 'select');
showsetting( lang('plugin/ymg6_vip', 'username'),'username','','text');  
showsetting( lang('plugin/ymg6_vip', 'order_submitdate'), array('submitdatebegin', 'submitdateend'), array($sstarttime, $sendtime), 'daterange');
showsetting( lang('plugin/ymg6_vip', 'order_confirmdate'), array('confirmbegin', 'confirmend'), array($cstarttime, $cendtime), 'daterange');
//showsetting('�ر���ʾ','signsetting[disable_info]','','text');  
//showsubmit('submit');  
showsubmit('searchsubmit');
showformfooter();
//������
showtablefooter();

}
if(submitcheck('searchsubmit', 1)) {
		
		showtips(lang('plugin/ymg6_vip', 'tips'));
//showformheader('plugins&identifier=ymg6_vip&pmod=admincp&test=1');
//���ʼ
showtableheader(lang('plugin/ymg6_vip', 'tableheader'));
showtablerow("",'',array(lang('plugin/ymg6_vip', 'order_id'),lang('plugin/ymg6_vip', 'order_trade_no'),lang('plugin/ymg6_vip', 'order_status'),lang('plugin/ymg6_vip', 'username'),lang('plugin/ymg6_vip', 'order_group_name'),lang('plugin/ymg6_vip', 'order_valitidy'),lang('plugin/ymg6_vip', 'order_price'),lang('plugin/ymg6_vip', 'order_submitdate'),lang('plugin/ymg6_vip', 'order_confirmdate')));
	$order_status = $_GET['order_status'];
	$username = $_GET['username'];
	$submitdatebegin = $_GET['submitdatebegin'];
	
	$submitdateend = $_GET['submitdateend'];
	
	$confirmbegin = $_GET['confirmbegin'];
	
	$confirmend = $_GET['confirmend'];
	
	$alldata = C::t('#ymg6_vip#ymg6_vip')->fetch_orderCondition($order_status,$username,$submitdatebegin,$submitdateend,$confirmbegin,$confirmend);
	//exit;
	
	foreach($alldata as $value){
		
		if($value['validity'] ==0){
			$value['validity'] =lang('plugin/ymg6_vip', 'validityinfo');
		}
		switch ($value['order_status']) {
				case 1:
					$value['order_status'] =lang('plugin/ymg6_vip', 'order_status_wait');
					break;
				case 2:
					$value['order_status'] =lang('plugin/ymg6_vip', 'order_status_sucess');
					break;
		}
		if($value['submitdate']  ==0){
			$value['submitdate']  ='N/A';
		}else{
			$value['submitdate']  =  date('Y-m-d H:i:s',$value['submitdate'] );
		}
		
		if($value['confirmdate']  ==0){
			$value['confirmdate']  ='N/A';
		}else{
				$value['confirmdate']  = date('Y-m-d H:i:s',$value['confirmdate'] );
		}
	
		showtablerow("",'',array($value['order_id'],$value['trade_no'],$value['order_status'],$value['username'],$value['group_name'],$value['validity'],$value['price'],$value['submitdate'],$value['confirmdate']));
	}
showtablefooter();
	}

//showtablefooter();  
//showformfooter();
?>