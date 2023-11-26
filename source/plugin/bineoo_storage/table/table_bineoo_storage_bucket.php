<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_bineoo_storage_bucket extends discuz_table{

	public function __construct() {
		$this->_table = 'bineoo_storage_bucket';
		$this->_pk = 'bucket';
		parent::__construct();
	}

	public function fetch_all(){
		return DB::fetch_all('SELECT * FROM %t',array($this->_table));
	}

	public function fetch_first($bucket=''){
		return DB::fetch_first('SELECT * FROM %t WHERE '.DB::field('bucket',$bucket),array($this->_table));
	}

	public function fetch_default(){
		return DB::fetch_first('SELECT * FROM %t WHERE '.DB::field('default',1),array($this->_table));
	}

	public function update_default($bucket=''){
		DB::update('bineoo_storage_bucket',array('default' => 0));
		DB::update('bineoo_storage_bucket',array('default' => 1),array('bucket'=>$bucket));
	}
}
?>