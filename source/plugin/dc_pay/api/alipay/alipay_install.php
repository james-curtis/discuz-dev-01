<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

class alipay_install extends api_install{
	public function __construct(){
		parent::__construct();
		$this->title=$this->_lang['install_title'];
		$this->des=$this->_lang['install_des'];
		$this->author=$this->_lang['install_author'];
		$this->version='v1.1.1';
		$this->logo='logo.gif';
		$this->mobile = 1;
	}
	public function install(){
		return true;
	}
	
	public function uninstall(){
		return true;
	}
	
	public function upgrade($version){
		
	}

}
?>