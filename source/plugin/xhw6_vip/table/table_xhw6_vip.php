<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_myrepeats.php 31512 2012-09-04 07:11:08Z monkey $
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_xhw6_vip extends discuz_table
{
	public function __construct() {
	    //global $_G;
		$this->_table = 'xhw6_vip';
		$this->_pk    = '';

		parent::__construct();
	}
	
	public function fetch_orderCondition($order_status=null,$username=null,$submitdatebegin=null,$submitdateend=null,$confirmbegin=null,$confirmend=null)
	{
		$condition = '';
		$condition .= $order_status ? ' AND order_status='. $order_status:'';
		$condition .= $username ? " AND username=". "'".$username."'":'';

		if($submitdatebegin and $submitdateend){
			$condition .=' AND (submitdate>='.strtotime($submitdatebegin.' 00:00:00').' AND submitdate<='.strtotime($submitdateend.' 23:59:59').')';
		}
		if($submitdatebegin and !$submitdateend){
				$condition .=$submitdatebegin? ' AND submitdate>='.strtotime($submitdatebegin.' 00:00:00'):'';
		}
		if($confirmbegin and $confirmend){
			$condition .=' AND (submitdate>='.strtotime($confirmbegin.' 00:00:00').' AND submitdate<='.strtotime($confirmend.' 23:59:59').')';
		}
		
		if($confirmbegin and !$confirmend){
			$condition .=$confirmbegin? ' AND submitdate>='.strtotime($confirmbegin.' 00:00:00'):'';
		}
		if($condition ==''){
			return  DB::fetch_all('SELECT * FROM  %t  ORDER BY submitdate desc', array($this->_table));
		}
				echo $condition;
		$condition = substr($condition,4);
		return  DB::fetch_all('SELECT * FROM  %t   where ' .$condition .' ORDER BY submitdate desc', array($this->_table));
		
	}
	public function fetch_ordercompleted_data($showNumber)
	{
		return DB::fetch_all('SELECT * FROM  %t   where order_status=2 and trade_no is not null  ORDER BY confirmdate desc  limit 8', array($this->_table));
	}
	
	public function insert_data($data)
	{
		return 	DB::insert($this->_table,$data);
	}
	
	public function  fetch_buy_order_id($order_id)
   {
		return DB::fetch_first('SELECT  *  FROM  %t  WHERE  order_id=%s', array($this->_table, $order_id));
	}
	
	public function update_by_order_id($order_id,$trade_no,$timestamp)
	{
		return DB::query("UPDATE  %t  SET  order_status=2,trade_no='".$trade_no. "' ,confirmdate = ".$timestamp."  WHERE order_id=%s", array($this->_table, $order_id));
	}
}

?>