<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
class yyhyowxpay_admin extends api_admin{

    public function dosave(){
        $config = $this->getconfig();
        $d = array(
            'partner'=>trim($_GET['partner']),
            'sign_type' => strtoupper(trim($_GET['sign_type'])),
            'input_charset' => strtolower(trim($_GET['input_charset'])),
            'transport'=>trim($_GET['transport']),
            'apiurl'=>trim($_GET['apiurl'])
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
        $config['key'] = $config['key']?substr($config['key'],0,1).'********'.substr($config['pubkey'],-4):'';

        showsetting($this->_lang['partner'], 'partner', $config['partner'], 'text');
        showsetting($this->_lang['key'], 'key', $config['key'], 'text');
        showsetting($this->_lang['sign_type'], 'sign_type', $config['sign_type']?$config['sign_type']:'MD5', 'text');
        showsetting($this->_lang['input_charset'], 'input_charset', $config['input_charset']?$config['input_charset']:CHARSET, 'text','','',$this->_lang['input_charset_msg']);
        showsetting($this->_lang['transport'], 'transport', $config['transport']?$config['transport']:'http','text','','',$this->_lang['transport_msg']);
        showsetting($this->_lang['apiurl'], 'apiurl', $config['apiurl']?$config['apiurl']:'http://pay.yyhyo.com/', 'text');
    }
}
?>