<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

defined('IN_DISCUZ') && defined('IN_ADMINCP') || exit('Access Denied');

if ($_GET['op'] == 'index') {
    if ($_GET['lid']) {
        $red_font = array('<font color=red>','</font>');
        $cur_aid = intval($_GET['aid']);
        $log_info = hwh_db('log')->fetch(array('id'=>intval($_GET['lid'])));
        if ($cur_aid) {
            $cur_att_info = C::t('forum_attachment')->fetch_all_by_id('aid',$cur_aid);
            $cur_att_desc = C::t('forum_attachment_n')->fetch($cur_att_info[$cur_aid]['tableid'],$cur_aid);
            $cur_filename = ' - '.$red_font[0].'"'.$cur_att_desc['filename'].'"'.$red_font[1];
            $cur_info = $cur_filename;
            $condition['aid'] = intval($_GET['aid']);
        }
        if (isset($_GET['uid'])) {
            $cur_uid = intval($_GET['uid']);
            $cur_userinfo = getuserbyuid($cur_uid);
            $cur_username = $cur_uid ? ' - '.$red_font[0].$cur_userinfo['username'].$red_font[1] : '';
            $cur_info = $cur_username;
            $condition['uid'] = $cur_uid;
        }
        if ($condition['uid']===0){
            $condition['useip'] = $log_info['useip'];
            $cur_username = ' - '.$red_font[0].$lang['guest'].'('.$log_info['useip'].')'.$red_font[1];
            $cur_info = $cur_username;
        }
    }
	//#page set
    $curpage  = max(1, $_G['page']);
    $limit    = max(20, $_G['tpp']);
    $start    = ($curpage - 1) * $limit;
    $count    = getcount(PLUGIN_ID.'_log', $condition);
    $tpl_page = multi($count, $limit, $curpage, NO_PAGE_URL);
    #\\
	$log_data = hwh_db('log')->fetch_range($start, $limit,$condition,'DESC');

	foreach ($log_data as $v) {
		$temp['uid'][] = $v['uid'];
		$temp['tid'][] = $v['tid'];
		$temp['aid'][] = $v['aid'];
	}

	$username = C::t('common_member')->fetch_all_username_by_uid($temp['uid']);
	$username[0] = $lang['guest'];
	$thread = C::t('forum_thread')->fetch_all_by_tid($temp['tid']);
	$att_info = C::t('forum_attachment')->fetch_all_by_id('aid',$temp['aid']);

	foreach ($log_data as $k => $v) {
		$att_desc = C::t('forum_attachment_n')->fetch($att_info[$v['aid']]['tableid'],$v['aid']);
		$table_rows.= showtablerow('', array('', 'class="td25 lightfont"', '', '', '', '', '','',''), array(
            '<input type="checkbox" value="' . $v['id'] . '" name="delete[]">',
            $v['id'],
            '<a href="'.PLUGIN_URL.'&mod='.$mod.'&ac=log&lid='.$v['id'].'&uid='.$v['uid'].'">'.$username[$v['uid']].'</a>',
            '<a href="'.PLUGIN_URL.'&mod='.$mod.'&ac=log&lid='.$v['id'].'&aid='.$v['aid'].'">'.$att_desc['filename'].'</a>',
            '<a href="forum.php?mod=viewthread&tid='.$v['tid'].'" target="_blank">'.$thread[$v['tid']]['subject'].'</a>',
            $att_info[$v['aid']]['downloads'],
            date('Y-m-d H:i:s',$v['dateline']),
            $v['useip'].' '.convertip($v['useip']),
            '<a href="'.PLUGIN_URL.'&mod='.$mod.'&ac=log&op=delete&id='.$v['id'].'&formhash='.FORMHASH.'">' . $lang['delete'] . '</a>',
        ), true);
	}

	showformheader(admin_url_hack(PLUGIN_URL . '&mod='.$mod.'&ac=log&op=delete', 'form'));
    showtableheader($clang['p'][5].$cur_info);
    showsubtitle(array('', 'id',$lang['download'].$lang['nav_user'], $lang['attachment'].$lang['name'], 'attach_thread', $clang['p'][3],  $lang['download'].$lang['connect_member_bindlog_date'],$lang['ip'], 'operation'));
    echo $table_rows;
    showsubmit('delete_log', 'delete', 'select_all', '<a href="'.PLUGIN_URL.'&mod='.$mod.'&ac=log&op=clear" style="background:#F00;color:#FFF" class="btn" onclick="if(!confirm(\''.$clang['p'][6].'?\'))return false;">'.$clang['p'][4].'</a>', $tpl_page);
    echo '<script type="text/JavaScript">document.getElementsByName("chkall")[0].setAttribute("onclick","checkAll(\'prefix\', this.form, \'delete\')");</script>';
    showtablefooter();
    showformfooter();
}elseif($_GET['op'] == 'delete'){
	if (submitcheck('delete_log') || ($_GET['id'] && $_GET['formhash'] == FORMHASH)) {
        if ($_GET['id']) $_GET['delete'][] = $_GET['id'];
        hwh_db('log')->delete($_GET['delete']);
        cpmsg('groups_setting_succeed', dreferer(), 'succeed');
    }
}elseif($_GET['op'] == 'clear'){
    hwh_db('log')->clear_table();
    cpmsg('groups_setting_succeed', dreferer(), 'succeed');
}else{
    hwh_404();
}