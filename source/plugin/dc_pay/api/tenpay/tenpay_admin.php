<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
class tenpay_admin extends api_admin{
	
	public function dosave(){
		$config = $this->getconfig();
		$d = array(
			'type'=>dintval($_GET['type']),
			'partner'=>trim($_GET['partner']),
		);
		$key = trim($_GET['key']);
		if($key!=substr($config['key'],0,1).'********'.substr($config['key'],-4)){
			$d['key']=$key;
		}else{
			$d['key']=$config['key'];
		}
		$this->saveconfig($d);
	}
	
	public function doset(){
		$config = $this->getconfig();
		$config['key'] = $config['key']?substr($config['key'],0,1).'********'.substr($config['key'],-4):'';
		showsetting($this->_lang['apitype'], array('type',array(array(1,$this->_lang['jsdz']),array(2,$this->_lang['dbjy']))), $config['type'], 'select');
		showsetting($this->_lang['partner'], 'partner', $config['partner'], 'text');
		showsetting($this->_lang['key'], 'key', $config['key'], 'text');
	}
}
?>