<?php
/*
 *Դ��磺www.ymg6.com
 *������ҵ���/ģ��������� ����Դ���
 *����Դ��Դ�������ռ�,��������ѧϰ����������������ҵ��;����������24Сʱ��ɾ��!
 *����ַ�������Ȩ��,�뼰ʱ��֪����,���Ǽ���ɾ��!
 */

if(!defined('IN_DISCUZ')) {  
    exit('Access Denied');  
}  
class plugin_ymg6_vip{
		function   global_cpnav_extra1()
		{
			//return "test13213213288888";
		}
}
class plugin_ymg6_vip_forum extends plugin_ymg6_vip
{
		function  global_cpnav_extra2()
		{
			//return "test132132132";
		}
		
		function  forumdisplay_middle()
	{
		
		//return "test132132132";
	}
	
	function index_status_extra_output(){
		//	$announcements=$this->_get_index_announcements($this->conf['dir'],$this->conf['speed'],$this->conf['width']);
			//$arrayshow = array('fdsfsd');
	/*	include template('ymg6_vip:showNote');
			return $return;*/
	}
	function  index_nav_extra(){
			global $_G;
			//$reply_message = str_replace(array('{name}', '{price}', '{priceunit}'), array($auction['name'], ($auction['typeid'] != 2 ? $auction['ext_price'] : ''), ($auction['typeid'] != 2 ? $_G['setting']['extcredits'][$auction['extid']]['title'] : '')), $reply_message);
			$noteContent = $_G['cache']['plugin']['ymg6_vip']['cjxixi_noteContent'];
			$isindexshow = $_G['cache']['plugin']['ymg6_vip']['cjxixi_isindexshow'];
			if($isindexshow){
				$order_completed_data = C::t('#ymg6_vip#ymg6_vip')->fetch_ordercompleted_data();
				$rand = mt_rand(0,count($order_completed_data)-1);
				$noteContent = str_replace(array('{datetime}','{username}','{usergroup}'),array(date('Y-m-d H:i:s',$order_completed_data[$rand]['submitdate'] ),$order_completed_data[$rand]['username'],$order_completed_data[$rand]['group_name']),$noteContent);
				include template('ymg6_vip:showNote');
			}
			//�������ַ�ֱ���滻��ok

			return $return;
	}
	
}

?>