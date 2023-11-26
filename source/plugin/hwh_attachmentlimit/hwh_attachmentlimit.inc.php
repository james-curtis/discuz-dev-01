<?php
/*
 *源码哥：www.ymg6.com
 *更多商业插件/模版免费下载 就在源码哥
 *本资源来源于网络收集,仅供个人学习交流，请勿用于商业用途，并于下载24小时后删除!
 *如果侵犯了您的权益,请及时告知我们,我们即刻删除!
 */

defined('IN_DISCUZ') || exit('Powered by Hymanwu.Com');
$config = include 'config.php';
define('PLUGIN_ID', $config['ID']);
define('PLUGIN_URL', defined('IN_ADMINCP') ? ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=' . dhtmlspecialchars($_GET['identifier']) . '&pmod=' . PLUGIN_ID : $_G['siteurl'] . 'plugin.php?id=' . PLUGIN_ID);
define('CUR_URL', ($_G['isHTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('PLUGIN_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('PLUGIN_ROOT', $_G['siteroot'] . 'source/plugin/' . PLUGIN_ID . '/');
define('PLUGIN_ASSETS', PLUGIN_ROOT . 'assets/');
define('CUR_LANG', currentlang());
define('NO_PAGE_URL', preg_replace('/(\?|&)page=\d*/is', '', CUR_URL));
$_GET['mod'] = $mod = !in_array($_GET['mod'], $config['MODULE_LIST']) ? $config['DEFAULT_MODULE'] : $_GET['mod'];
$_GET['ac']  = $ac  = empty($_GET['ac']) ? $config['DEFAULT_AC'] : $_GET['ac'];
$_GET['op']  = $op  = empty($_GET['op']) ? $config['DEFAULT_OP'] : $_GET['op'];
if (!preg_match('/^[_0-9a-z]+$/i',$mod) || !preg_match('/^[_0-9a-z]+$/i',$ac) || !preg_match('/^[_0-9a-z]+$/i',$op)) system_error('request_tainting');
$mod == $config['ADMIN_MODULE'] && defined('IN_ADMINCP') || exit('Access Denied');
if (!isset($_G['cache']['plugin'][PLUGIN_ID])) loadcache('plugin');
$cfg   = $_G['cache']['plugin'][PLUGIN_ID];
$plang = include PLUGIN_PATH . 'language/' . CUR_LANG . '.php';
$clang = $plang[$mod];
include PLUGIN_PATH . 'source/function/common.php';
require PLUGIN_PATH . 'source/module/' . $mod . '.php';