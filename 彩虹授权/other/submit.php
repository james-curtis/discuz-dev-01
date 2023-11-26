<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>免签约支付宝即时到账交易接口</title>
</head>
<?php
/* *
 * 功能：即时到账交易接口接入页
 * 
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */
require 'inc.php';
require_once("kxpay/kxpay.config.php");
require_once("kxpay/lib/kxpay_submit.class.php");

/**************************请求参数**************************/
		
        $notify_url = $siteurl."kxpay_notify.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = $siteurl."kxpay_return.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //商户网站订单系统中唯一订单号，必填
		//授权域名
		$url = $_POST['url'];
		//授权的QQ
		$qq = $_POST['qq'];
		//登录账号
		$user = $_POST['user'];
		//登录密码
		$pass = $_POST['pass'];
		//支付方式
        $type = $_POST['type'];
        //授权的域名
        $name = "白云授权网在线购买授权";
		//付款金额
        $money = $conf['money'];
		//站点名称
        $sitename = "白云授权网在线购买授权";
        //必填
        //订单描述

/************************************************************/
//将订单信息写入数据库，以便于在异步通知时进行处理
$DB->query("insert into `auth_order` ( `name`, `trade_no`, `url`, `qq`, `user`, `pass`,`money`,`type`,`addtime`) values ('$name', '$out_trade_no', '$url', '$qq', '$user', '$pass', '$money','$type', '$date')");
//构造要请求的参数数组，无需改动
$parameter = array(
		"pid" => trim($alipay_config['partner']),
		"type" => $type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"name"	=> $name,
		"money"	=> $money,
		"sitename"	=> $sitename
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter);
echo $html_text;

?>
</body>
</html>