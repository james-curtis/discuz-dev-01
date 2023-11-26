<?php
/**
 *      [Discuz!] (C)2015-2099 DARK Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: paylist.inc.php 10389 2016-12-02 19:04:15Z wang11291895@163.com $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$_lang = lang('plugin/dc_pay');
loadcache('dc_paysecurity');
$code = $_G['cache']['dc_paysecurity']['code'];
if(empty($code))cpmsg($_lang['securityempty'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=security', 'error');
$securitycode = getcookie('dcpaysecurity');
if(!$securitycode||authcode($securitycode)!=md5($code.FORMHASH)){
	if(submitcheck('submitcheck')){
		$securitycode = trim($_GET['securitycode']);
		if($code!=md5(md5($securitycode).$_G['cache']['dc_paysecurity']['salt']))
			cpmsg($_lang['securitycodeerror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
		$authcode = authcode(md5($code.FORMHASH),'ENCODE');
		dsetcookie('dcpaysecurity', $authcode, 1800, 1, true);
	}else{
		$url = ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist';
		$str = '<div class="infobox"><form method="post" action="'.$url.'"><input type="hidden" name="formhash" value="'.FORMHASH.'"><input type="hidden" name="submitcheck" value="true">
					<br />'.$_lang['securitycode_1'].'<br />
					<input name="securitycode" value="" type="password"/>
					<br />
					<p class="margintop"><input type="submit" class="btn" name="submit" value="'.cplang('submit').'">
					</p></form><br /></div>';
		echo $str;
		die();
	}
	
}
//clearstatcache(true,'./data/config.php');
$config = @include DISCUZ_ROOT.'/source/plugin/dc_pay/data/config.php';
if($_GET['act']=='install'){
	$f=trim($_GET['f']);
	if(submitcheck('confirmed')){
		if(!install($f))
			cpmsg($_lang['installerror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
		cpmsg($_lang['installsucceed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'succeed',array('identify' =>$f));
	}
	cpmsg($_lang['intstallcheck'],'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=install&f='.$f,'form', array('identify' => $f));
}elseif($_GET['act']=='upgrade'){
	$f=trim($_GET['f']);
	if(!$config[$f])
		cpmsg($_lang['error'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
	if(submitcheck('confirmed')){
		if(!upgrade($f))
			cpmsg($_lang['ugradeerror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
		cpmsg($_lang['ugradesucceed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'succeed',array('title' =>$config[$f]['title']));
	}
	cpmsg($_lang['ugradecheck'],'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=upgrade&f='.$f,'form', array('title' =>$config[$f]['title']));
}elseif($_GET['act']=='uninstall'){
	$f=trim($_GET['f']);
	if(!$config[$f])
		cpmsg($_lang['error'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
	if(submitcheck('confirmed')){
		$paytitle = $config[$f]['title'];
		if(!uninstall($f))
			cpmsg($_lang['uninstallerror'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
		cpmsg($_lang['uninstallsucceed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'succeed',array('title' =>$paytitle));
	}
	cpmsg($_lang['unintstallcheck'],'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=uninstall&f='.$f,'form', array('title' =>$config[$f]['title']));
}elseif($_GET['act']=='set'){
	$f=trim($_GET['f']);
	if(!$config[$f])
		cpmsg($_lang['error'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
	if(submitcheck('submit')){
		C::import('api/admin','plugin/dc_pay',false);
		C::import($f.'/admin','plugin/dc_pay/api',false);
		$modstr = $f.'_admin';
		if (class_exists($modstr,false)){
			$mobj = new $modstr();
			if(in_array('dosave',get_class_methods($mobj))){
				$mobj->dosave();
			}
			$config[$f]['alias']=trim($_GET['alias']);
			$config[$f]['enable']=intval($_GET['enable']);
			$config[$f]['mobileenable']=intval($_GET['mobileenable']);
			writeconfig($config);
			cpmsg($_lang['setsucceed'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'succeed');
		}
		cpmsg($_lang['error'], 'action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist', 'error');
	}
	showformheader('plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&f='.$f.'&act=set','enctype');
	showtableheader($_lang['set'].'('.$config[$f]['title'].')', '');
	showsetting($_lang['enable'], 'enable', $config[$f]['enable']);
	if($config[$f]['mobile']){
		showsetting($_lang['mobile'], 'mobileenable', $config[$f]['mobileenable'],'radio','','',$_lang['mobile_msg']);
	}
	showsetting($_lang['alias'], 'alias', $config[$f]['alias'],'text','','',$_lang['aliasmsg']);
	C::import('api/admin','plugin/dc_pay',false);
	C::import($f.'/admin','plugin/dc_pay/api',false);
	$modstr = $f.'_admin';
	if (class_exists($modstr,false)){
		$mobj = new $modstr();
		if(in_array('doset',get_class_methods($mobj))){
			$mobj->doset();
		}
	}
	showtablefooter();
	showsubmit('submit');
	showformfooter();
	exit;
}
$payarr = array();
$payerror = false;
$entrydir = DISCUZ_ROOT.'./source/plugin/dc_pay/api';
C::import('api/install','plugin/dc_pay',false);
if(file_exists($entrydir)) {
	$d = dir($entrydir);
	while($f = $d->read()) {
		if($f!='.'&&$f!='..'&&is_dir($entrydir.'/'.$f)){
			if(!preg_match("/^[a-z0-9_\-]+$/i", $f))continue;
			C::import($f.'/install','plugin/dc_pay/api',false);
			$modstr = $f.'_install';
			if (class_exists($modstr,false)){
				$obj = new $modstr();
				$payarr[$f] = array(
					'logo'=>$obj->logo,
					'title'=>$obj->title,
					'des'=>$obj->des,
					'author'=>$obj->author,
					'version'=>$obj->version,
				);
			}
		}
	}
}
showtips($_lang['paytips']);
showtableheader($_lang['isinstall'], '');
showsubtitle(array('',$_lang['logo'], $_lang['title'],$_lang['des'],$_lang['author'],$_lang['version'],$_lang['enable'],$_lang['caozuo']));
foreach($config as $k => $c){
	$verchk = false;
	if($payarr[$k]){
		$verchk = $payarr[$k]['version']>$c['version'];
		unset($payarr[$k]);
	}else{
		$payerror = true;
		unset($config[$k]);
		continue;
	}
    if (stripos($c['logo'],'/') !== false) {
        $logo = $c['logo'];
    } else {
        $logo = 'source/plugin/dc_pay/api/'.$k.'/'.$c['logo'];
    }
	showtablerow('', array('width="20"', 'width="100"','class="td28"','class="td28"','class="td28"'), 
			array('',
			'<img src="'.$logo.'" />',
			$c['title'],
			$c['des'],
			$c['author'],
			$c['version'],
			$c['enable']?$_lang['yes']:$_lang['no'],
			'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=set&f='.$k.'">'.$_lang['set'].'</a>  '.($verchk?'<a href="?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=upgrade&f='.$k.'" style="color:#FF0000">['.$_lang['upgrade'].']</a> ':'').' <a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=uninstall&f='.$k.'">'.$_lang['uninstall'].'</a>'));
}
showtablefooter();
showtableheader($_lang['isnoinstall'], '');
showsubtitle(array('',$_lang['logo'], $_lang['title'],$_lang['des'],$_lang['author'],$_lang['version'],$_lang['caozuo']));
$entrydir = DISCUZ_ROOT.'./source/plugin/dc_pay/api';
C::import('api/install','plugin/dc_pay',false);
foreach($payarr as $k => $c){
    if (stripos($c['logo'],'static') !== false) {
        $logo = $c['logo'];
    } else {
        $logo = 'source/plugin/dc_pay/api/'.$k.'/'.$c['logo'];
    }
	showtablerow('', array('width="20"', 'width="100"','class="td28"','class="td28"', 'class="td28"'), array('','<img src="'.$logo.'" />',$c['title'],$c['des'],$c['author'],$c['version'],'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=dc_pay&pmod=paylist&act=install&f='.$k.'">'.$_lang['install'].'</a>'));
}
showtablefooter();
if($payerror){
	writeconfig($config);
}
function uninstall($name){
	global $config;
	if(!preg_match("/^[a-z0-9_\-]+$/i", $name))return;
	C::import('api/install','plugin/dc_pay',false);
	C::import($name.'/install','plugin/dc_pay/api',false);
	$modstr = $name.'_install';
	if (class_exists($modstr,false)){
		$mobj = new $modstr();
		if(in_array('uninstall',get_class_methods($mobj))){
			if($mobj->uninstall()===false)
				return false;
		}
		unset($config[$name]);
		return writeconfig($config);
	}
	return false;
}
function install($name){
	global $config;
	if($config[$name]||!preg_match("/^[a-z0-9_\-]+$/i", $name))
		return;
	C::import('api/install','plugin/dc_pay',false);
	C::import($name.'/install','plugin/dc_pay/api',false);
	$modstr = $name.'_install';
	if (class_exists($modstr,false)){
		$mobj = new $modstr();
		if(in_array('install',get_class_methods($mobj))){
			if($mobj->install()===false)
				return false;
		}
		$config[$name]['title']=$mobj->title;
		$config[$name]['des']=$mobj->des;
		$config[$name]['author']=$mobj->author;
		$config[$name]['version']=$mobj->version;
		$config[$name]['logo']=$mobj->logo;
		$config[$name]['mobile']=$mobj->mobile;
		return writeconfig($config);
	}
	return false;
}
function upgrade($name){
	global $config;
	if(!$config[$name]||!preg_match("/^[a-z0-9_\-]+$/i", $name))
		return;
	C::import('api/install','plugin/dc_pay',false);
	C::import($name.'/install','plugin/dc_pay/api',false);
	$modstr = $name.'_install';
	if (class_exists($modstr,false)){
		$mobj = new $modstr();
		if(in_array('upgrade',get_class_methods($mobj))){
			if($mobj->upgrade($config[$name]['version'])===false)
				return false;
		}
		$config[$name]['title']=$mobj->title;
		$config[$name]['des']=$mobj->des;
		$config[$name]['author']=$mobj->author;
		$config[$name]['version']=$mobj->version;
		$config[$name]['logo']=$mobj->logo;
		$config[$name]['mobile']=$mobj->mobile;
		return writeconfig($config);
	}
	return false;
}
function writeconfig($config){
	$configdata = 'return '.var_export($config, true).";\n\n";
	if($fp = @fopen(DISCUZ_ROOT.'/source/plugin/dc_pay/data/config.php', 'wb')) {
		fwrite($fp, "<?php\n//plugin dc_pay config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
		fclose($fp);
		return true;
	}
	return false;
}
?>