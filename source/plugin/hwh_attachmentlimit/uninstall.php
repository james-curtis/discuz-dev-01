<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

defined('IN_DISCUZ') && defined('IN_ADMINCP') || exit('Powered by Hymanwu.Com');
$config = include 'config.php';
$plugin_id = $config['ID'];

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_{$plugin_id}_log` ;

EOF;
runquery($sql);

$delete_files = array(
    'discuz_plugin_'.$plugin_id.'.xml',
    'discuz_plugin_'.$plugin_id.'_SC_GBK.xml',
    'discuz_plugin_'.$plugin_id.'_SC_UTF8.xml',
    'discuz_plugin_'.$plugin_id.'_TC_BIG5.xml',
    'discuz_plugin_'.$plugin_id.'_TC_UTF8.xml',
    'uninstall.php',
    'install.php',
);

foreach ($delete_files as $file) {
    $path = DISCUZ_ROOT . 'source/plugin/'.$plugin_id.'/'.$file;
    file_put_contents($path, '');
    @unlink($path);
}

@unlink(DISCUZ_ROOT . 'data/sysdata/cache_plugin_' . $plugin_id . '_conf.php');
@unlink(DISCUZ_ROOT . 'data/sysdata/cache_plugin_' . $plugin_id . '_log.php');

$finish = TRUE;