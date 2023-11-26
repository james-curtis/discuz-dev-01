<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
C::import('api/paybase','plugin/dc_pay',false);
class api_admin extends api_paybase{
	protected $_lang = array();
	public function __construct(){
		$this->_lang = @include DISCUZ_ROOT.'./source/plugin/dc_pay/language/'.$this->getextend().'.'.currentlang().'.php';
		if(empty($this->_lang))$this->_lang = @include DISCUZ_ROOT.'./source/plugin/dc_pay/language/'.$this->getextend().'.php';
	}
	public function dosave(){
		
	}
	
	public function doset(){
		
	}
}
?>