<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_types extends discuz_table
{
	public function __construct() {

		$this->_table = 'ticket_types';
		$this->_pk    = 't_id';
		parent::__construct();
	}
	
	public function fetch_all(){
		return DB::fetch_all("SELECT * FROM %t ORDER BY t_order ASC",array($this->_table));
	}
	
}

?>