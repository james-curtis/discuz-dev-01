<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$ticketId = intval($_GET['item']);
if(!$ticketId) showmessage(lang('plugin/ticket', 'slang14'));
$ticket = C::t("#ticket#main")->fetch($ticketId);
if(!$ticket || $ticket['pid']) showmessage(lang('plugin/ticket', 'slang14'));
if($ticket['uid'] != $_G['uid'] && !$admin) showmessage(lang('plugin/ticket', 'slang24'));

if(submitcheck("submit")){
	$data = $updata = array();
	if(!strip_tags($_GET['content'])) showmessage(lang('plugin/ticket', 'slang25'));
	$data['content'] 	= addslashes($_GET['content']);
	$data['dateline']	= $_G['timestamp'];
	$data['lastline']	= $_G['timestamp'];
	$data['uid']		= $_G['uid'];
	$data['username']	= $_G['username'];
	$data['pid']		= $ticketId;
	$data['t_id']		= $ticket['t_id'];
	C::t("#ticket#main")->insert($data);
	$updata['status'] 	= $admin?1:($ticket['status']?2:0);
	$updata['lastline'] = $_G['timestamp'];
	if(isemail($extends['email'])){
		sendmail(
			$extends['email'],
			lang('plugin/ticket', 'slang47'),
			$_GET['content']
		);
	}
	C::t("#ticket#main")->update($ticketId,$updata);
	if($admin){
		showmessage(lang('plugin/ticket', 'slang26'),"plugin.php?id=ticket&mod=admin&type=1");
	}else{
		showmessage(lang('plugin/ticket', 'slang13'),"plugin.php?id=ticket&mod=list&type=1");
	}
}else{
	$extend = str_replace("\r\n", "\n", $pVars['extend']);
	$ruler = explode("\n",$extend);
	$extends = array();
	foreach ($ruler as $v) {
		$extends[] = explode("=", $v);
	}
	$tickets = array();
	$tickets['ticket'] = $ticket;
	$tickets['ticket']['extends'] = dunserialize($tickets['ticket']['extends']); 
	$tickets['replies'] = C::t("#ticket#main")->fetch_all_by_pid($ticketId);
}
?>