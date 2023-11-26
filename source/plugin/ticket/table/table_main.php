<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_main extends discuz_table
{
	public function __construct() {
		$this->_table = 'ticket_main';
		$this->_pk    = 'id';
		parent::__construct();
	}
	
	public function fetch_all_notclose($uid,$start,$limit){
		if($uid){
			return DB::fetch_all("SELECT * FROM %t WHERE pid=0 and uid=%d and status < 4 ORDER BY lastline DESC limit %d,%d",array($this->_table,$uid,$start,$limit));
		}else{
			return DB::fetch_all("SELECT * FROM %t WHERE pid=0 and status < 4 ORDER BY lastline DESC limit %d,%d",array($this->_table,$start,$limit));
		}
		
	}

	public function count_notclose($uid){
		if($uid){
			return DB::result_first("SELECT count(*) FROM %t WHERE pid=0 and uid=%d and status < 4",array($this->_table,$uid));
		}else{
			return DB::result_first("SELECT count(*) FROM %t WHERE pid=0 and status < 4",array($this->_table));
		}
		
	}

	public function fetch_all_close($uid,$start,$limit){
		if($uid){
			return DB::fetch_all("SELECT * FROM %t WHERE pid=0 and uid=%d and status > 3 ORDER BY lastline DESC limit %d,%d",array($this->_table,$uid,$start,$limit));
		}else{
			return DB::fetch_all("SELECT * FROM %t WHERE pid=0 and status > 3 ORDER BY lastline DESC limit %d,%d",array($this->_table,$start,$limit));
		}
	}	

	public function count_close($uid){
		if($uid){
			return DB::result_first("SELECT count(*) FROM %t WHERE pid=0 and uid=%d and status > 3 ",array($this->_table,$uid));
		}else{
			return DB::result_first("SELECT count(*) FROM %t WHERE pid=0 and status > 3 ",array($this->_table,$uid));
		}
	}

	public function fetch_all_by_pid($pid){
		return DB::fetch_all("SELECT * FROM %t WHERE pid=%d ORDER BY dateline ASC",array($this->_table,$pid));
	}

	public function delete_by_tid($t_id){
		return DB::query("DELETE FROM %t WHERE t_id= %d",array($this->_table,$t_id));
	}
	
}

?>