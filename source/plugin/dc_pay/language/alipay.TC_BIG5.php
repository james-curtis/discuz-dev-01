<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
return array(
	'install_title'=>'支付寶',
	'install_des'=>'全球領先的獨立第三方支付平臺',
	'install_author'=>'大創網絡',
	'qrcode'=>'是否掃碼模式',
	'pid'=>'應用ID (APPID)',
	'pidmsg'=>'新接口設置比較復雜，請閱讀 <a href="http://addon.discuz.com/?@dc_pay.plugin.76454" target="_blank">設置教程</a>',
	'key'=>'RSA2(SHA256)私鑰',
	'keymsg'=>'用戶自己使用工具生成的密鑰對，公鑰上傳到商戶平臺。<br /><a href="https://doc.open.alipay.com/docs/doc.htm?treeId=291&articleId=105971&docType=1" target="_blank">生成工具下載</a>',
	'pubkey'=>'支付寶RSA2(SHA256)公鑰',
	'pubkeymsg'=>'支付寶公鑰，請到商戶平臺查看',
);
?>