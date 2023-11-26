<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class table_dc_pay_api extends discuz_table
{
	public function __construct() {
		$this->_table = 'dc_pay_api';
		$this->_pk    = 'plugin';
		parent::__construct();
	}
}

?>