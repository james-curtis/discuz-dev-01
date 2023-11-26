<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
class alipay_admin extends api_admin{
	
	public function dosave(){
		$config = $this->getconfig();
		$d = array(
			'pid'=>trim($_GET['pid']),
			'qrcode'=>intval($_GET['qrcode']),
		);
		$key = trim($_GET['key']);
		if($key!=substr($config['key'],0,1).'********'.substr($config['key'],-4)){
			$d['key']=$key;
		}else{
			$d['key']=$config['key'];
		}
		$pubkey = trim($_GET['pubkey']);
		if($pubkey!=substr($config['pubkey'],0,1).'********'.substr($config['pubkey'],-4)){
			$d['pubkey']=$pubkey;
		}else{
			$d['pubkey']=$config['pubkey'];
		}
		$this->saveconfig($d);
	}
	
	public function doset(){
		$config = $this->getconfig();
		$config['key'] = $config['key']?substr($config['key'],0,1).'********'.substr($config['key'],-4):'';
		$config['pubkey'] = $config['pubkey']?substr($config['pubkey'],0,1).'********'.substr($config['pubkey'],-4):'';
		
		showsetting($this->_lang['pid'], 'pid', $config['pid'], 'text','','',$this->_lang['pidmsg']);
		showsetting($this->_lang['key'], 'key', $config['key'], 'text','','',$this->_lang['keymsg']);
		showsetting($this->_lang['pubkey'], 'pubkey', $config['pubkey'], 'text','','',$this->_lang['pubkeymsg']);
		showsetting($this->_lang['qrcode'], 'qrcode', $config['qrcode']);
	}
}
?>