<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//添加提示信息

//表格开始


if(!submitcheck('insetData')) {

showtips(lang('plugin/ymg6_vip', 'tips'));
showtableheader(lang('plugin/ymg6_vip', 'moni_data'));
showformheader('plugins&operation=config&do='.$pluginid.'&pmod=adminrepeats&identifier=ymg6_vip', 'testhd');  
//showtableheader();  
//showsubmit('testhd', "aaaa", "订单状态".': <br/><input name="srchusername" value="'.htmlspecialchars($_GET['srchusername']).'" class="txt" />&nbsp;&nbsp;'.$Plang['repeat'].': <input name="srchrepeat" value="'.htmlspecialchars($_GET['srchrepeat']).'" class="txt" />', $searchtext);

showsetting(lang('plugin/ymg6_vip', 'user_id'),'userid','','text');  
showsetting(lang('plugin/ymg6_vip', 'group_id'),'groupid','','text');



//showsetting('关闭提示','signsetting[disable_info]','','text');  
//showsubmit('submit');  
showsubmit('insetData');
showformfooter();
//表格结束
showtablefooter();

}
if(submitcheck('insetData', 1)) {
	

	$uid = $_GET['userid'];
	$groupid = $_GET['groupid'];

	$userName = C::t('common_member')->fetch_all_username_by_uid(array($uid));
	$groupinfo = C::t('common_usergroup')->fetch_all($groupid);

showtips(lang('plugin/ymg6_vip', 'tips'));
showtableheader(lang('plugin/ymg6_vip', 'moni_data'));
	if(!$userName){
		showtablerow ("","","<font color='red'><b>UserID is  not found</b></font>");
		exit();
	}
	if(!$groupinfo){
		showtablerow("","","<font color='red'><b>GroupID is not found</b></font>");
		exit();
	}
	$insertdata = array(
			'order_id' =>dgmdate(TIMESTAMP, 'YmdHis').random(20),
			'order_status' =>2,
			'uid' =>$uid,
			'username'=>$userName[$uid],
			'trade_no'=>dgmdate(TIMESTAMP, 'YmdHis').rand(10, 99),
			'group_id'=>$groupid,
			'group_name'=>$groupinfo[$groupid]['grouptitle'],
			'extcreditstitle'=> lang('plugin/ymg6_vip', 'extcreditstitle'),
			'extcredits'=> lang('plugin/ymg6_vip', 'extcredits'),
			'price'=>0.01,
			'validity'=>0,
			'submitdate'=>time(),
			'confirmdate'=>time(),
			'ip'=>$_G['clientip'],
			'pay_type'=>'alipay',
	);			
	C::t('#ymg6_vip#ymg6_vip')->insert_data($insertdata);

	$strtmp= lang('plugin/ymg6_vip', 'tishi_info');
	showtablerow('','',"<font color='red'><b>$strtmp</b></font>");
	showtablefooter();
	
	
}
?>