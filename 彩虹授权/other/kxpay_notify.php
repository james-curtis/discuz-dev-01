<?php
/* *
 * 功能：白云支付异步通知页面
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 */
require_once("./inc.php");
require_once("kxpay/kxpay.config.php");
require_once("kxpay/lib/kxpay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//开心支付交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];

	$srow=$DB->get_row("SELECT * FROM auth_order WHERE trade_no='{$out_trade_no}' limit 1");
	$row=$DB->get_row("SELECT * FROM auth_site WHERE uid='".$srow['qq']."' limit 1");
	$row1=$DB->get_row("SELECT * FROM auth_site WHERE 1 order by sign desc limit 1");
	$sign=$row1['sign']+1;
	$authcode=md5(random(32).$srow['qq']);

	if ($_GET['trade_status'] == 'TRADE_SUCCESS') {
		
		if($srow['status']==0){
				$DB->query("update `auth_order` set `status` ='1',`endtime` ='{$date}' where `out_trade_no`='{$out_trade_no}'");
				if($row!='')exit("<script language='javascript'>alert('授权平台已存在该QQ，请使用“添加站点”！');history.go(-1);</script>");
				$sql="insert into `auth_site` (`uid`,`zid`,`user`,`pass`,`user_jf`,`url`,`date`,`authcode`,`active`,`sign`) values ('".$srow['qq']."','1','".$srow['user']."','".$srow['pass']."','0','".$srow['url']."','".$date."','".$authcode."','1','".$sign."')";
				$DB->query($sql);
				exit("<script language='javascript'>alert('添加授权成功！');window.location.href='../../get';</script>");
			}else{
				exit("<script language='javascript'>alert('此订单已经处理过了！')</script>");
			}
    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";
}
?>