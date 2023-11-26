<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ticketId = intval($_GET['item']);
if(!$ticketId) showmessage(lang('plugin/ticket', 'slang14'), 'plugin.php?id=ticket&mod=list&type=2', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'right'));
$ticket = C::t("#ticket#main")->fetch($ticketId);
if(!$ticket || $ticket['pid']) showmessage(lang('plugin/ticket', 'slang14'), 'plugin.php?id=ticket&mod=list&type=2', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'right'));

if($_GET['type'] == 'rate'){
	if($ticket['uid'] != $_G['uid']) showmessage(lang('plugin/ticket', 'slang15'), 'plugin.php?id=ticket&mod=list&type=2', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'right'));

	if(submitcheck('submit')){
		$data = array();
		$data['status'] = intval($_GET['rate']);
		if($data['status']<4) showmessage(lang('plugin/ticket', 'slang16'));
		C::t("#ticket#main")->update($ticketId,$data);
		showmessage(lang('plugin/ticket', 'slang17'), 'plugin.php?id=ticket&mod=list&type=2', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'right'));
	}else{
		if($ticket['status']>3)
			showmessage(lang('plugin/ticket', 'slang18'), 'plugin.php?id=ticket&mod=list&type=2', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'error'));
		include template("ticket:rate");
	}
}elseif($_GET['type'] == 'close'){
	if($_GET['hash'] != FORMHASH)
		showmessage('submit_invalid', 'plugin.php?id=ticket&mod=admin', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'error'));
	C::t("#ticket#main")->update($ticketId,array("status"=>9));
	showmessage(lang('plugin/ticket', 'slang19'), 'plugin.php?id=ticket&mod=admin', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'right'));

}else{
	showmessage(lang('plugin/ticket', 'slang20'), 'plugin.php?id=ticket&mod=list&type=1', array(), array('locationtime' => 3, 'showdialog'=>2, 'alert' => 'error'));
}
exit;
?>