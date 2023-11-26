<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
return array(
	'install_title'=>'支付宝',
	'install_des'=>'全球领先的独立第三方支付平台',
	'install_author'=>'大创网络',
	'qrcode'=>'是否扫码模式',
	'pid'=>'应用ID (APPID)',
	'pidmsg'=>'新接口设置比较复杂，请阅读 <a href="http://addon.discuz.com/?@dc_pay.plugin.76454" target="_blank">设置教程</a>',
	'key'=>'RSA2(SHA256)私钥',
	'keymsg'=>'用户自己使用工具生成的密钥对，公钥上传到商户平台。<br /><a href="https://doc.open.alipay.com/docs/doc.htm?treeId=291&articleId=105971&docType=1" target="_blank">生成工具下载</a>',
	'pubkey'=>'支付宝RSA2(SHA256)公钥',
	'pubkeymsg'=>'支付宝公钥，请到商户平台查看',
);
?>