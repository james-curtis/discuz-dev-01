<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($_GET['action'] == 'delete_attach'){
	if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'hash_error',
		));
		exit;
	}
	if(empty($_G['uid'])) {
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'need_login',
		));
		exit;
	}
	$attach = C::t('forum_attachment')->fetch(intval($_GET['aid']));

	if(!$attach || $attach['uid'] != $_G['uid']){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'file_not_exit',
		));
		exit;
	}
	$attach['tableid'] = $attach['tableid'] == 127 ? 'unused' : $attach['tableid'];
	$attachment = DB::fetch_first('SELECT * FROM '.DB::table('forum_attachment_'.$attach['tableid']).' WHERE '.DB::field('aid',$attach['aid']));
	if(!$attachment){
		echo json_encode(array(
			'code'=>'succeed1',
		));
		exit;
	}
	list($ossClient,$oss_set) = oss_client();
	$object = $oss_set['attachurl'].'forum/'.$attachment['attachment'];
	$ossClient->deleteObject($oss_set['bucket'], $object);
	echo json_encode(array(
		'code'=>'succeed',
	));
	exit;

}else if($_GET['action'] == 'access'){
	if(empty($_G['uid'])) {
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'need_login',
		));
		exit;
	}
	$oss_set = oss_set();
	if(!$oss_set['Access_Key_Secret'] || !$oss_set['Access_Key_ID']){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'Access Key Secret or Access Key ID is empty',
		));
		exit;
	}
	$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch_default();
	if(!$bucket){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>'Default Bucket was not found',
		));
		exit;
	}
	require_once libfile('function/upload');
	$upload_config = getuploadconfig($_G['uid'], $_GET['fid']);

	$key= $oss_set['Access_Key_Secret'];
	$timestamp = TIMESTAMP;
	$end = $timestamp + 30;
	$dir = $oss_set['attachurl'].'forum/'.date('Ym').'/'.date('d').'/';
	$base64_policy = base64_encode(json_encode(array(
		'expiration'=>gmt_iso8601($end),
		'conditions'=>array(
			array(0=>'content-length-range', 1=>0, 2=>$upload_config['max']*1024),
			array(0=>'starts-with', 1=>'$key', 2=>$dir)
		),
	)));
	$callback_sign = md5($_G['config']['security']['authkey'].md5($_G['uid'].$timestamp));

	echo json_encode(array(
		'accessid' => $oss_set['Access_Key_ID'],
		'host' => 'http://'.$bucket['bucket'].'.'.$bucket['region'].'.aliyuncs.com',
		'policy' => $base64_policy,
		'signature' => base64_encode(hash_hmac('sha1', $base64_policy, $key, true)),
		'expire' => $end,
		'callback' => base64_encode(json_encode(array(
			'callbackUrl'=>$_G['siteurl'].'source/plugin/bineoo_storage/callback/index.php', 
			'callbackBody'=>'timestamp='.$timestamp.'&sign='.$callback_sign.'&usein=forum&type='.$_GET['type'].'&uid='.$_G['uid'].'&filename=${object}&filesize=${size}&width=${imageInfo.width}', 
			'callbackBodyType'=>"application/x-www-form-urlencoded"
		))),
		'dir' => $dir,
	));
	exit;
}else if($_GET['action'] == 'upload_avatar'){
	$avatarsign = authcode($_GET['avatarsign'],'DECODE');
	$avatarsign = explode('|', $avatarsign);
	if(!$avatarsign || count($avatarsign) != 3 || $avatarsign[2] !== md5($avatarsign[0].md5($avatarsign[1].$avatarsign[0]))){
		echo json_encode(array(
			'code' => 'error',
			'msg' => 'sign error',
		));
		exit;
	}

	require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/class/CropAvatar.php';
	$crop = new CropAvatar($_GET['avatar_data'],$_FILES['avatar_file'],$avatarsign['0'],1);
	echo json_encode(array(
		'code' => 'succeed',
		'msg' => $crop->getMsg(),
		'result' => $crop->getResult(),
	));
	exit;
}else if($_GET['action'] == 'avatar'){
	$ucenter = $_GET['ucenter'];
	$size = in_array($_GET['size'], array('big', 'middle', 'small')) ? $_GET['size'] : 'middle';
	$real = $_GET['real'];
	$uid = sprintf("%09d", intval($_GET['uid']));
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$file = $ucenter.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).($real ? '_real' : '').'_avatar_'.$size.'.jpg';
	if(!is_file($file) && !$_G['bineoo_storage']['ossClient']->doesObjectExist($_G['bineoo_storage']['oss_set']['bucket'],$file)){
		$file = $ucenter.'/images/noavatar_'.$size.'.gif';
	}
	dheader('location:'.object_url($file));
}else if($_GET['action'] == 'object_list'){
	if(!in_array($_G['uid'], explode(',', $_G['bineoo_storage']['oss_set']['manage_uids']))) {
		echo json_encode(array(
			'code'=>'error',
			'msg'=>lang('plugin/bineoo_storage', 'notin_manage_uids')
		));
		exit();
	}
	if(!$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket'])){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>lang('plugin/bineoo_storage', 'no_default_bucket')
		));
		exit();
	}
	list($ossClient,$oss_set) = oss_client($bucket['region']);

	if(!$ossClient->doesBucketExist($bucket['bucket'])){
		C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
		echo json_encode(array(
			'code'=>'error',
			'msg'=>lang('plugin/bineoo_storage', 'no_bucket')
		));
		exit();
	}

	if(!empty($_GET['prefix'])){
		$prefix = $_GET['prefix'];
		if(substr($prefix,-1) == '/'){
			$prefix = substr($prefix,0,strlen($prefix)-1);
		}
		$prefix_arr = explode('/', $prefix);
		$prefix_nav = array();
		foreach ($prefix_arr as $i => $data) {
			$key = '';
			for ($j=0; $j <= $i ; $j++) {
				$key .= $prefix_arr[$j].'/';
			}
			$prefix_nav[] = array(
				'key' => $key,
				'prefix' => $data,
			);
		}
	}
	$options = array(
		'max-keys' => 100,
		'prefix' => $_GET['prefix'] ? $_GET['prefix'] : '',
		'marker'  => $_GET['marker'] ? $_GET['marker'] : '',
	);
	$result = $ossClient->listObjects($bucket['bucket'],$options);
	$dir_list = $object_list = array();
	$html = $marker = '';
	foreach ($result->getPrefixList() as $data) {
		$prefix = $data->getPrefix();
		$html .= '<tr>';
		$html .= '	<td><span class="oss-icon-folder"></span></td>';
		$html .= '	<td><a href="plugin.php?id=bineoo_storage&action=object&bucket='.$bucket['bucket'].'&prefix='.$prefix.'">'.str_replace($_GET['prefix'], '', $prefix).'</a></td>';
		$html .= '	<td>-</td>';
		$html .= '	<td width=70 class="object-position object-position-'.(is_dir($prefix) ? 'succeed' : 'error').'">'.(is_dir($prefix) ? lang('plugin/bineoo_storage', 'exist') : lang('plugin/bineoo_storage', 'not_exist')).'</td>';
		$html .= '	<td>-</td>';
		$html .= '	<td></td>';
		$html .= '</tr>';
		$marker = $prefix;
	}
	foreach ($result->getObjectList() as $data) {
		if($data->getKey() == $_GET['prefix']){
			continue;
		}
		$html .= '<tr>';
		$html .= '	<td><input type="checkbox" class="object-check" name="object[]" value="'.$data->getKey().'"></td>';
		$html .= '	<td>'.str_replace($_GET['prefix'], '', $data->getKey()).'</td>';
		$html .= '	<td>'.sizecount($data->getSize()).'</td>';
		$html .= '	<td width=70 class="object-position object-position-'.(is_file($data->getKey()) ? 'succeed' : 'error').'">'.(is_file($data->getKey()) ? lang('plugin/bineoo_storage', 'exist') : lang('plugin/bineoo_storage', 'not_exist')).'</td>';
		$html .= '	<td>'.date('Y-m-d H:i',strtotime($data->getLastModified())).'</td>';
		$html .= '	<td class="text-right table-action"><a class="dialog" href="javascript:;" url="plugin.php?id=bineoo_storage&action=delete&formhash='.formhash().'&bucket='.$bucket['bucket'].'&object='.$data->getKey().'">'.lang('plugin/bineoo_storage', 'delete').'</a></td>';
		$html .= '</tr>';
		$marker = $data->getKey();
	}

	echo json_encode(array(
		'code'=>'succeed',
		'html'=>diconv($html,$_G['charset'],'UTF-8'),
		'marker'=>$marker,
	));
	exit();

}