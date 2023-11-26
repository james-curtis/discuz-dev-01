<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
define('ALLDIR','./source/plugin/bineoo_storage');

function image_replace($data){
	global $_G;
	if(stripos($data[0], 'bineoo-storage') !== false){
		return $data[0];
	}
	$data[0] = str_replace(substr($data[0], 0, 4), '<img ', $data[0]);
	preg_match('/width=["|\']([^"]*)["|\']/i',$data[0],$width);
	preg_match('/height=["|\']([^"]*)["|\']/i',$data[0],$height);
	preg_match('/style=["|\']([^"]*)["|\']/i',$data[0],$style);
	$n_w = $n_h = 0;
	foreach (explode(';', $style[1]) as $s) {
		$s = preg_replace("/\s/","",trim($s));
		if(stripos($s, ':') !== false){
			$s = explode(':', $s);
			if($s[0] == 'width'){
				$n_w = str_replace('px', '', $s[0]);
			}
			if($s[0] == 'height'){
				$n_h = str_replace('px', '', $s[0]);
			}
		}
	}
	$data[0] = str_replace($data[1], object_url($data[1],array(
		'width'=>$width[1] ? $width[1] : $n_w,
		'height'=>$height[1] ? $height[1] : $n_h,
	)), $data[0]);
	preg_match('/zoomfile=["|\']([^"]*)["|\']/i', $data[0], $zoom);
	if($zoom){
		$data[0] = str_replace($zoom[1], object_url($zoom[1],array(
			'width'=>$width[1] ? $width[1] : $n_w,
			'height'=>$height[1] ? $height[1] : $n_h,
		)), $data[0]);
	}else{
		preg_match('/file=["|\']([^"]*)["|\']/i', $data[0], $zoom);
		if($zoom){
			$data[0] = str_replace($zoom[1], object_url($zoom[1],array(
				'width'=>$width[1] ? $width[1] : $n_w,
				'height'=>$height[1] ? $height[1] : $n_h,
			)), $data[0]);
		}
	}
	preg_match('/orig=["|\']([^"]*)["|\']/i', $data[0], $zoom);
	if($zoom){
		$data[0] = str_replace($zoom[1], object_url($zoom[1],array(
			'width'=>$width[1] ? $width[1] : $n_w,
			'height'=>$height[1] ? $height[1] : $n_h,
		)), $data[0]);
	}
	preg_match('/zsrc=["|\']([^"]*)["|\']/i', $data[0], $zoom);
	if($zoom){
		$data[0] = str_replace($zoom[1], object_url($zoom[1],array(
			'width'=>$width[1] ? $width[1] : $n_w,
			'height'=>$height[1] ? $height[1] : $n_h,
		)), $data[0]);
	}
	return $data[0];
}

function link_replace($data){
	if(stripos($data[0], 'bineoo-storage') !== false){
		return $data[0];
	}
	$isimage = false;
	foreach (explode('|', '.jpg|.gif|.bmp|.bnp|.png|.webp') as $v) {
		if(stripos(strtolower($data[1]), $v) !== false){
			$isimage = true;
		}
	}
	$data[0] = $isimage ? str_replace($data[1], object_url($data[1]), $data[0]) : $data[0];
	$data[0] = str_replace(substr($data[0], 0, 2), '<a ', $data[0]);
	return $data[0];
}

function srcipt_replace($data){
	if(stripos($data[0], 'bineoo-storage') !== false){
		return $data[0];
	}
	$data[0] = str_replace($data[1], object_url($data[1]), $data[0]);
	$data[0] = str_replace(substr($data[0], 0, 7), '<script ', $data[0]);
	return $data[0];
}

function object_url($object='',$param,$style=false){
	global $_G;
	if(!$object){
		return '';
	}
	//return 'http://oss.xhw6.cn/uc_server/images/noavatar_big.gif';
	$oss_set = $_G['bineoo_storage']['oss_set'];
	if($oss_set['extra_tag']){
		foreach ($oss_set['extra_tag'] as $tag) {
			$tag_l = strlen(trim($tag));
			if($tag_l && strtolower(substr($object, 0, $tag_l)) == $tag){
				$object = str_replace(substr($object, 0, $tag_l), '', $object);
			}
		}
	}

	$object_ext = strtolower(pathinfo($object, PATHINFO_EXTENSION));
	if(stripos($object, '?')){
		$object_arr = explode('?', $object);
		$object_ext = strtolower(pathinfo($object_arr[0], PATHINFO_EXTENSION));
		if(!in_array($object_ext, array('gif','jpeg','png','bmp','jpg','webp','js','css'))){
			return $object;
		}
	}
	if($oss_set['direct_oss'] != 1) {
		if(is_file($object) || is_file($object_arr[0])){
			return $object;
		}
	}
	if(in_array(strtolower(substr($object, 0, 6)), array('http:/', 'https:', 'ftp://', 'rtsp:/', 'mms://'))) {
		return $object;
	}
	if(stripos($object, 'noavatar') !== false){
	  return $oss_set['domain'].$object;
	}
	if(stripos($object, '/data/avatar') !== false){
	  return $oss_set['domain'].$object;
	}
	if(stripos($object, 'avatar') !== false){
		return $object;
	}
	if(stripos($object, 'forum.php') !== false){
		return $object;
	}
	foreach ($oss_set['except_tag'] as $except) {
		//$except = stripslashes($except);
		if(stripos($object, $except) !== false){
			if ($oss_set['direct_oss']){
				return $oss_set['domain'].$object;
			} else {
				return $object;
			}
		}
	}
	$url = $oss_set['domain'].$object;
	if($oss_set['image_style'] && !in_array($object_ext, array('js','css'))){
		return $url.'/'.$oss_set['image_style'];
	}
	//$image_style = 'image/auto-orient,1/watermark,image_c3RhdGljL2ltYWdlL2NvbW1vbi93YXRlcm1hcmsucG5nP3gtb3NzLXByb2Nlc3M9aW1hZ2UvcmVzaXplLHBfMTAwL2JyaWdodCwwL2NvbnRyYXN0LDA,t_100,g_se,y_10,x_10';
	$width = $height = false;
	if(intval($param['width']) && stripos($param['width'], '%') === false){
		$width = intval($param['width']) > 4096 ? 'w_4096' : 'w_'.intval($param['width']);
	}
	if(intval($param['height']) && stripos($param['height'], '%') === false){
		$height = intval($param['height']) > 4096 ? 'h_4096' : 'h_'.intval($param['height']);
	}
	if($width && $height){
		$url .= "?x-oss-process=".($oss_set['image_style']?$oss_set['image_style']:'image')."/resize,m_fixed,$height,$width";
	}else if(!$width && $height){
		$url .= "?x-oss-process=".($oss_set['image_style']?$oss_set['image_style']:'image')."/resize,$height";
	}else if($width && !$height){
		$url .= "?x-oss-process=".($oss_set['image_style']?$oss_set['image_style']:'image')."/resize,$width";
	}
	return $url;
}

function avatar_url($uid,$size=middle) {
	//return 'uc_server/avatar.php?uid=1&size=small';
	//$more = explode('&',explode('?',$url)[1]);
	//$param = array();
	//$param['uid'] = explode('=',$more[0])[1];
	//$param['size'] = @explode('=',$more[1])[1];
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$file = $oss_set['domain'].str_replace($_G['siteurl'], '', $_G['setting']['ucenterurl']).'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
	return $file;
}