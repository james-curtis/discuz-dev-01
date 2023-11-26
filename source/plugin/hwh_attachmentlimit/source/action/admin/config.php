<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

defined('IN_DISCUZ') && defined('IN_ADMINCP') || exit('Access Denied');

require_once libfile('function/cache');
$cache_conf_path = DISCUZ_ROOT . 'data/sysdata/cache_plugin_' . PLUGIN_ID . '_conf.php';
$cache_conf      = is_file($cache_conf_path) ? include $cache_conf_path : array();
if (!$_G['cache']['forums']) loadcache('forums');

if ($_GET['op'] == 'index') {

    require_once libfile('function/forumlist');
    $forums     = forumselect(0, 1, 0, 1);
    $forums_arr = procforums($forums);

    if(!$cache_conf) showtips('<li>'.$clang['p'][7].'</li>');
    showtableheader('runwizard_particular');
    showsubtitle(array('forums_admin_name', '', 'operation'));
    $btn_lang_pre = $cfg['mode']==1 ? '' : $clang['p'][0].$lang['forum'];
    $html = array(
        '<a href="' . PLUGIN_URL . '&mod=admin&ac=config&op=group&fid=0" class="btn">' . $btn_lang_pre . $lang['setting_styles_threadprofile_group'] . '</a>');
    if ($cfg['mode']==1) {
        $tr_rows = showtablerow('', array('', 'align="right" class="td23 lightfont"', 'class="td31"'), array('<strong class="lightnum">' . $lang['all'] . $lang['forums'] . '</strong>', '', $html[0],
        ), true);
    } else {
        foreach ($forums_arr as $k => $v) {
            $forumname[$k] = $v['type'] == 'group' ? $v['name'] : ($v['type'] == 'sub' ? '<div id="cb_' . $v['id'] . '" class="childboard">' . $v['name'] . '</div>' : '<div class="board">' . $v['name'] . '</div>');
            $tr_rows .= showtablerow('', array('', 'align="right" class="td23 lightfont"', 'class="td31"'), array(
                $forumname[$k],
                $v['type'] != 'group' ? '(fid:' . $v['id'] . ')' : '',
                $v['type'] != 'group' ? fid_replace($html[0], $v['id']) : '',
            ), true);
        }
    }
    echo $tr_rows;
    showtablefooter();

} elseif ($_GET['op'] == 'group') {
    $cur_forum       = $_G['cache']['forums'][$_GET['fid']];


    if (!submitcheck('submit')) {
        showtips('<li>"'.$clang['p'][1].'"'.$clang['p'][2].'</li>');
        showformheader(admin_url_hack(PLUGIN_URL . '&mod=admin&ac=config&op=group', 'form'));

        showtableheader('[' . ($_GET['fid'] ? $cur_forum['name'] : $lang['all'] . $lang['forums']) . ']' . $lang['setting_styles_threadprofile_group']);

        showsubtitle(array(

            $lang['usergroups'] . 'id',

            $lang['usergroups_title'],

            $lang['usergroups'] . $lang['type'],

            $clang['p'][1],

        ));

        $groups = C::t('common_usergroup')->fetch_all_by_type(null, null, true);

        foreach ($groups as $k => $v) {

            if ($v['type'] == 'system') {

                $grouptitle = $lang['usergroups_system'];

            } elseif ($v['type'] == 'member') {

                $grouptitle = $lang['usergroups_member'];

            } elseif ($v['type'] == 'special') {

                $grouptitle = $lang['usergroups_special'];

            } elseif ($v['type'] == 'specialadmin') {

                $grouptitle = $lang['usergroups_specialadmin'];

            }

            $checked = in_array($k, array_keys($cache_conf)) ? ' checked="checked"' : '';

            showtablerow(
                '',
                array('class="td25"', 'class="td31"', 'class="td31"'),
                array(
                    $k,
                    '<span style="color:' . $v['color'] . '">' . $v['grouptitle'] . '</span>',
                    $grouptitle,
                    '<input type="number" class="txt" name=maxnum[' . $v['groupid'] . '] value="' . $cache_conf[$_GET['fid']][$v['groupid']] . '">',
                ));
            if ($v['type'] != $groups[$k + 1]['type'] && $groups[$k + 1]) {
                showtablerow(
                    '',
                    array('colspan="6"'),
                    array(
                        '<hr style="height:1px;border:none;border-top:2px solid #DEEFFB;">',
                    ));
            }

        }
        showhiddenfields(array('fid' => intval($_GET['fid'])));
        showsubmit('submit', 'submit');
        showtablefooter();
        showformfooter();
    } else {
        foreach ($_GET['maxnum'] as $groupid => $maxnum) {
            $cache_conf[$_GET['fid']][$groupid] = is_numeric($maxnum) ? intval($maxnum) : '';
        }
        $cache_array .= "return " . arrayeval($cache_conf) . ";\n";
        writetocache('plugin_' . PLUGIN_ID . '_conf', $cache_array);
        cpmsg($lang['groups_setting_succeed'], admin_url_hack(PLUGIN_URL . '&mod=admin&ac=config'), 'succeed');
    }
}else{
    hwh_404();
}