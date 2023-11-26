<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_dc_pay {
	private $_var;
	public function __construct() {
		$this->plugin_dc_pay();
	}
	public function plugin_dc_pay(){
		global $_G;
		$this->_var = $_G['cache']['plugin']['dc_pay'];
	}
	public function global_usernav_extra3(){
		if($this->_var['open']&&$this->_var['topshow'])
			return '<a href="home.php?mod=spacecp&ac=plugin&op=credit&id=dc_pay:buycredit" target="_blank">'.lang('plugin/dc_pay','buycredit').'</a> <span class="pipe">|</span>';
	}
}

?>