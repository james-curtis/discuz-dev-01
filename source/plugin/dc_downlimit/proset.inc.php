<?php
/*
 *讯幻网：www.xhkj5.com
 *更多商业插件/模版免费下载 就在讯幻网
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$seturl='plugins&operation=config&do='.$pluginid.'&identifier=dc_downlimit&pmod=proset';
$act=$_GET['act'];
if($act=='setsave'&&submitcheck('submit')){
	$fid=dintval($_GET['fid']);
	$groups=dintval($_GET['groupid'],true);
	$maxs=dintval($_GET['max'],true);
	$frees=dintval($_GET['free'],true);
	$creditnew=$_GET['creditnew'];
	foreach($groups as $k =>$v){
		$data[$groups[$k]]['max']=$maxs[$k];
		$data[$groups[$k]]['free']=$frees[$k];
	}
	$data=daddslashes(serialize($data));
	DB::query("REPLACE INTO ".DB::table('dc_downlimit')."(fid,data) VALUES('$fid','$data')");
	cpmsg(plang('succeed'), 'action='.$seturl.'&act=set&fid='.$fid, 'succeed');
}elseif($act=='set'){
	$fid=dintval($_GET['fid']);
	showformheader($seturl.'&act=setsave&fid='.$fid);
	$r=DB::fetch_first("select * from ".DB::table('forum_forum')." where fid='$fid'");
	if(!$r){
		cpmsg(plang('nofid'), 'action='.$seturl, 'error');
	}
	$rd=DB::fetch_first("select * from ".DB::table('dc_downlimit')." where fid='$fid'");
	$data=dunserialize($rd['data']);
	showtips(plang('ftips'),'tips',true,plang('xxset').'->'.$r['name'].'->'.plang('daylimit'));
	$groupselect = array();
	$query=DB::query("SELECT * FROM ".DB::table('common_usergroup')." WHERE groupid not in(4,5,6,7,8,9)");
	while($group = DB::fetch($query)) {
		$group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
		$groupselect[$group['type']][] = array('groupid'=>$group['groupid'],'grouptitle'=>$group['grouptitle']);
	}
	foreach($groupselect as $k=>$gs){
		showtableheader($lang['usergroups_'.$k.''], '');
		showsubtitle(array('',plang('group'),plang('ddowns'),plang('ddfree')));
		foreach($gs as $g){
			showtablerow('', array('', '',''), array("<input type=\"hidden\" name=\"groupid[]\" value=\"$g[groupid]\">",$g['grouptitle'],'<input type="number" name="max[]" value="'.$data[$g['groupid']]['max'].'" />','<input type="number" name="free[]" value="'.$data[$g['groupid']]['free'].'" />'));
		}
		showtablefooter();
	}
	showsubmit('submit', 'submit');
	showformfooter();
}else{
	require_once libfile('function/forumlist');
	showtips(plang('tips'));
	showtableheader(plang('xxset'), '');
	showsubtitle(array('',plang('fid'),plang('set')));
	$forums=forumselect(FALSE,1);
	foreach($forums as $key => $values){
		foreach($values as $ks => $value){
			if($ks=='name'){
				showtablerow('', array(), array('',$value,''));
			}elseif($ks=='sub'){
				foreach($value as $k =>$v){
					$str='';
					$str.='&nbsp;&nbsp;<a href="admin.php?action='.$seturl.'&act=set&fid='.$k.'">'.plang('set').'</a>';
					showtablerow('', array(), array('','&nbsp;&nbsp;&nbsp;&nbsp;'.$v,$str));
					foreach($values['child'][$k] as $k1=>$v1){
						$str='';
						$str.='&nbsp;&nbsp;<a href="admin.php?action='.$seturl.'&act=set&fid='.$k1.'">'.plang('set').'</a>';
						showtablerow('', array(), array('','&nbsp;&nbsp;&nbsp;&nbsp;------'.$v1,$str));
					}
				
				}
				
			}
		}
	}
	showtablefooter();
}

function plang($str) {
	return lang('plugin/dc_downlimit', $str);
}
?>