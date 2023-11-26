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

if(empty($_G['uid'])) {
	showmessage('to_login', null, array(), array('showmsg' => true, 'login' => 1));
}
if(!in_array($_G['uid'], explode(',', $_G['bineoo_storage']['oss_set']['manage_uids']))) {
	showmessage(lang('plugin/bineoo_storage', 'notin_manage_uids'));
}

$_GET['mod'] = in_array($_GET['mod'], array('bucket','local','cache','upload','oss_exist','local_exist','list_object','delete_local')) ? $_GET['mod'] : 'bucket';


if(!$_G['bineoo_storage']['oss_set']['Access_Key_ID'] || !$_G['bineoo_storage']['oss_set']['Access_Key_Secret']) {
	sshowmessage(lang('plugin/bineoo_storage', 'access_key_empty'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
}

if($_GET['mod'] == 'bucket'){
	$_GET['action'] = in_array($_GET['action'], array('bucket','sync','create','object','info','delete')) ? $_GET['action'] : 'bucket';
	switch ($_GET['action']) {
		case 'bucket':
				if(submitcheck('editsubmit')) {
					if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
						sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					if(!$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['default'])){
						sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
					list($ossClient,$oss_set) = oss_client($bucket['region']);

					if(!$ossClient->doesBucketExist($bucket['bucket'])){
						C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
						sshowmessage(lang('plugin/bineoo_storage', 'no_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
					require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/CorsConfig.php';
					require_once DISCUZ_ROOT.'./source/plugin/bineoo_storage/OSS/Model/CorsRule.php';
					$corsConfig = new CorsConfig();
					$rule = new CorsRule();
					$rule->addAllowedHeader('*');
					$rule->addAllowedOrigin('*');
					$rule->addAllowedMethod('GET');
					$rule->addAllowedMethod('POST');
					$rule->addAllowedMethod('PUT');
					$rule->addAllowedMethod('DELETE');
					$rule->addAllowedMethod('HEAD');
					$rule->setMaxAgeSeconds(600);
					$corsConfig->addRule($rule);

					$ossClient->putBucketCors($bucket['bucket'],$corsConfig);
					C::t('#bineoo_storage#bineoo_storage_bucket')->update_default(addslashes($bucket['bucket']));
					loadcache('bineoo_storage_setting');
					$oss_set = dunserialize($_G['cache']['bineoo_storage_setting']);
					$oss_set['bucket'] = $bucket['bucket'];
					$oss_set['region'] = $bucket['region'];
					$oss_set['domain'] = $bucket['domain'];
					$oss_set['internal'] = intval($_GET['internal']);
					$oss_set['image_style'] = $bucket['image_style'];
					savecache('bineoo_storage_setting',serialize($oss_set));
					sshowmessage(lang('plugin/bineoo_storage', 'bucket_default_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
				}
				$bucket_list = array();
				foreach (C::t('#bineoo_storage#bineoo_storage_bucket')->fetch_all() as $bucket) {
					$bucket_list[] = $bucket;
				}
			break;
		
		case 'sync':

				$bucket_list = array();
				foreach (C::t('#bineoo_storage#bineoo_storage_bucket')->fetch_all() as $data) {
					$bucket_list[$data['bucket']] = $data;
				}
				$result_list = $_G['bineoo_storage']['ossClient']->listBuckets()->getBucketList();

				foreach ($result_list as $result) {
					$bucket_name = $result->getName();
					$data = array(
						'bucket' => $bucket_name,
						'region' => $result->getLocation(),
						'domain' => '',
						'create_time' => strtotime($result->getCreateDate()),
						'default' => 0,
					);
					list($ossClient,$oss_set) = oss_client($data['region']);

					$data['acl'] = $ossClient->getBucketAcl($data['bucket']);
					$Cname = $ossClient->getBucketCname($data['bucket']);
					foreach ($Cname->getCnames() as $domain) {
						if(empty($data['domain'])){
							$data['domain'] = $domain['Domain'];
						}
					}

					if($bucket_list[$data['bucket']]){
						$oss_newset = dunserialize($_G['cache']['bineoo_storage_setting']);

						$oss_newset['domain'] = $data['domain'];
						savecache('bineoo_storage_setting',serialize($oss_newset));

						C::t('#bineoo_storage#bineoo_storage_bucket')->update($data['bucket'],array(
							'acl' => $data['acl'],
							'domain' => $data['domain'],
						));
					}else{
						C::t('#bineoo_storage#bineoo_storage_bucket')->insert($data);
					}
					unset($bucket_list[$data['bucket']]);
					
				}
				if($bucket_list){
					foreach ($bucket_list as $bucket) {
						C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
					}
				}
				sshowmessage(lang('plugin/bineoo_storage', 'bucket_sync_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
			break;

		case 'create':
				if(submitcheck('editsubmit')) {
					if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
						sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					if(!isset($_G['bineoo_storage']['oss_set']['region_list'][$_GET['region']])){
						sshowmessage(lang('plugin/bineoo_storage', 'error_region_list'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					list($ossClient,$oss_set) = oss_client($_GET['region']);
					try {
						$result = $ossClient->createBucket($_GET['bucket'],$_GET['acl']);
						if(!empty($_GET['domain'])){
							$ossClient->addBucketCname($_GET['bucket'], $_GET['domain']);
						}
						C::t('#bineoo_storage#bineoo_storage_bucket')->insert(array(
							'bucket' => addslashes($_GET['bucket']),
							'region' => addslashes($_GET['region']),
							'acl' => addslashes($_GET['acl']),
							'domain' => addslashes($_GET['domain']),
							'create_time' => TIMESTAMP,
							'internal' => intval($_GET['internal']),
						));
						sshowmessage(lang('plugin/bineoo_storage', 'create_bucket_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					} catch (Exception $e) {
						sshowmessage(lang('plugin/bineoo_storage', 'create_bucket_failed'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
				}
			break;
			
		case 'object':
				if(!$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket'])){
					sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
				}
				list($ossClient,$oss_set) = oss_client($bucket['region']);

				if(!$ossClient->doesBucketExist($bucket['bucket'])){
					C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
					sshowmessage(lang('plugin/bineoo_storage', 'no_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
				}

				if(submitcheck('editsubmit')) {
					if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
						sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					if(!is_array($_GET['object'])){
						sshowmessage(lang('plugin/bineoo_storage', 'no_chose_object'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					if($_GET['object_do'] == 'delete'){
						$ossClient->deleteObjects($bucket['bucket'], $_GET['object']);
						sshowmessage(lang('plugin/bineoo_storage', 'object_delete_succeed'), oss_url('&action=object&bucket='.$bucket['bucket'].'&prefix='.$_GET['prefix']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					}else if($_GET['object_do'] == 'download'){
						if($_GET['object']){
							foreach ($_GET['object'] as $object) {
								if(stripos($prefix,'/') !== false){
									dmkdir(DISCUZ_ROOT.str_replace(strrchr($object,'/'), '', $object));
								}
								$ossClient->getObject($bucket['bucket'], $object, array(
									OssClient::OSS_FILE_DOWNLOAD => DISCUZ_ROOT.$object,
								));
								if(intval($_GET['delete_oss']) && is_file($object)){
									$ossClient->deleteObject($bucket['bucket'], $object);
								}
							}
						}
						sshowmessage(lang('plugin/bineoo_storage', 'object_download_succeed'), oss_url('&action=object&bucket='.$bucket['bucket'].'&prefix='.$_GET['prefix']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					}
					sshowmessage(lang('plugin/bineoo_storage', 'no_chose_object_do'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
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
					'marker'  => '',
				);
				$result = $ossClient->listObjects($bucket['bucket'],$options);
				$dir_list = $object_list = array();
				foreach ($result->getPrefixList() as $data) {
					$dir_list[] = array(
						'prefix' => $data->getPrefix(),
					);
				}
				foreach ($result->getObjectList() as $data) {
					if($data->getKey() == $_GET['prefix']){
						continue;
					}
					$object_list[] = array(
						'key' => $data->getKey(),
						'size' => sizecount($data->getSize()),
						'StorageClass' => $data->getStorageClass(),
						'LastModified' => strtotime($data->getLastModified()),
						'isimage' => in_array(strtolower(pathinfo($data->getKey(), PATHINFO_EXTENSION)),array('gif','jpeg','png','bmp','jpg','webp')) ? 1 : 0,
					);
				}
			break;
			
		case 'info':
				if(!$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket'])){
					sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
				}
				list($ossClient,$oss_set) = oss_client($bucket['region']);

				if(!$ossClient->doesBucketExist($bucket['bucket'])){
					C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
					sshowmessage(lang('plugin/bineoo_storage', 'no_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
				}

				if(submitcheck('editsubmit')) {
					if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
						sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					$ossClient->putBucketAcl($bucket['bucket'], $_GET['acl']);
					try {
						if(!empty($bucket['domain'])){
							$ossClient->deleteBucketCname($bucket['bucket'], $bucket['domain']);
						}
						if(!empty($_GET['domain'])){
							$ossClient->addBucketCname($bucket['bucket'], $_GET['domain']);
						}
						C::t('#bineoo_storage#bineoo_storage_bucket')->update(addslashes($bucket['bucket']),array(
							'acl' => addslashes($_GET['acl']),
							'domain' => addslashes($_GET['domain']),
							'image_style' => addslashes($_GET['image_style']),
							'internal' => intval($_GET['internal']),
						));
						$oss_newset = dunserialize($_G['cache']['bineoo_storage_setting']);

						$oss_newset['domain'] = addslashes($_GET['domain']);
						$oss_newset['image_style'] = addslashes($_GET['image_style']);
						$oss_newset['internal'] = intval($_GET['internal']);
						savecache('bineoo_storage_setting',serialize($oss_newset));
						sshowmessage(lang('plugin/bineoo_storage', 'bucket_edit_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					} catch (Exception $e) {
						sshowmessage(lang('plugin/bineoo_storage', 'bucket_edit_failed'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
				}
			break;
			
		case 'delete':
				if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
					sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
				}
				if(!$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket'])){
					sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
				}
				list($ossClient,$oss_set) = oss_client($bucket['region']);
				if($_GET['object']){
					try{
						$ossClient->deleteObject($bucket['bucket'],$_GET['object']);
						sshowmessage(lang('plugin/bineoo_storage', 'object_delete_succeed'), oss_url('&action=object&bucket='.$bucket['bucket']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					}catch(Exception $e){
						sshowmessage(lang('plugin/bineoo_storage', 'object_delete_failed'), oss_url('&action=object&bucket='.$bucket['bucket']), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
				}else{
					if($bucket['default']){
						sshowmessage(lang('plugin/bineoo_storage', 'bucket_delete_failed_by_default'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
					}
					try{
						$ossClient->deleteBucket($bucket['bucket']);
						C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
						sshowmessage(lang('plugin/bineoo_storage', 'bucket_delete_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
					}catch(Exception $e){
						sshowmessage(lang('plugin/bineoo_storage', 'bucket_delete_failed'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
					}
				}
			break;
			
		default:
			break;
	}

}else if($_GET['mod'] == 'local'){
	if(submitcheck('editsubmit')) {
		if(empty($_GET['formhash']) || $_GET['formhash'] != formhash()) {
			sshowmessage(lang('plugin/bineoo_storage', 'error_hash'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
		}
		$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket']);
		if(!$bucket){
			sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
		}
		list($ossClient,$oss_set) = oss_client($bucket['region'],$_G['bineoo_storage']['oss_set']['internal']);
		if(!$ossClient->doesBucketExist($bucket['bucket'])){
			C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
			sshowmessage(lang('plugin/bineoo_storage', 'not_found_bucket_tips'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
		}

		if(is_array($_GET['object'])){
			$count = $success = 0;
			foreach ($_GET['object'] as $object) {
				if(is_file($object)){
					$ossClient->multiuploadFile($bucket['bucket'], $object, $object);
					if($ossClient->doesObjectExist($bucket['bucket'], $object)){
						if(intval($_GET['delete_local'])){
							@unlink(DISCUZ_ROOT.'./'.$object);
						}
						$success++;
					}
				}
				$count++;
			}
		}
		sshowmessage(lang('plugin/bineoo_storage', 'object_upload_count',array('success'=>$success,'error'=>$count - $success)), oss_url('&mod=local&prefix='.$_GET['prefix']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
	}else{
		$prefix = '';
		if(!empty($_GET['prefix'])){
			$prefix = $_GET['prefix'];
			if(substr($prefix,0,1) == '/' || substr($prefix,0,1) == '.' || stripos($prefix,'//') !== false){
				sshowmessage(lang('plugin/bineoo_storage', 'prefix_error'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
			}
			if(substr($prefix,-1) == '/'){
				$prefix = substr($prefix,0,strlen($prefix)-1);
			}
			if(!is_dir(DISCUZ_ROOT.'./'.$prefix)){
				sshowmessage(lang('plugin/bineoo_storage', 'prefix_not_dir'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
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
		$local = local_list(DISCUZ_ROOT.($prefix ? './'.$prefix : ''));
		$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch_default();
	}

}else if($_GET['mod'] == 'upload'){

	$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket']);
	if(!$bucket){
		sshowmessage(lang('plugin/bineoo_storage', 'no_default_bucket'), '', array(), array('showdialog' => 1, 'showmsg' => true, 'closetime' => 2));
	}
	list($ossClient,$oss_set) = oss_client($bucket['region'],$_G['bineoo_storage']['oss_set']['internal']);
	if(!$ossClient->doesBucketExist($bucket['bucket'])){
		C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
		sshowmessage(lang('plugin/bineoo_storage', 'not_found_bucket_tips'), oss_url(), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
	}
	$object = $_GET['object'];
	if(is_file($object)){

		try{
			$ossClient->multiuploadFile($bucket['bucket'], $object, $object);
			sshowmessage(lang('plugin/bineoo_storage', 'upload_succeed'), oss_url('&mod=local'), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
		}catch(Exception $e){
			sshowmessage(lang('plugin/bineoo_storage', 'upload_failed'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
		}
		if($ossClient->doesObjectExist($bucket['bucket'], $object)){
			sshowmessage(lang('plugin/bineoo_storage', 'upload_succeed'), oss_url('&mod=local'), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
		}else{
			sshowmessage(lang('plugin/bineoo_storage', 'upload_failed'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
		}
	}else if(is_dir($object)){
		try{
			$ossClient->uploadDir($bucket['bucket'], $object, $object);
			upload_dir($ossClient,$bucket['bucket'],$object);
			sshowmessage(lang('plugin/bineoo_storage', 'dir_upload_succeed'), oss_url('&mod=local&prefix='.$_GET['prefix']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
		}catch(Exception $e){
			sshowmessage(lang('plugin/bineoo_storage', 'upload_failed'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
		}
	}else{
		sshowmessage(lang('plugin/bineoo_storage', 'file_not_found'), oss_url('&mod=local'), array(), array('alert'=>'error','showdialog' => 1,'locationtime' => 2));
	}
}else if($_GET['mod'] == 'delete_local'){
	
	$local_file = DISCUZ_ROOT.$_GET['object'];
	if(is_file($local_file)){
		@unlink($local_file);
	}
	if(is_dir($local_file)){
		require_once libfile('function/cloudaddons');
		cloudaddons_cleardir($local_file);
	}
	sshowmessage(lang('plugin/bineoo_storage', 'dir_delete_succeed'), oss_url('&mod=local&prefix='.$_GET['prefix']), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
}else if($_GET['mod'] == 'oss_exist'){
	$bucket = C::t('#bineoo_storage#bineoo_storage_bucket')->fetch($_GET['bucket']);
	if(!$bucket){
		echo json_encode(array(
			'code'=>'error',
			'msg'=>diconv(lang('plugin/bineoo_storage', 'no_default_bucket'),$_G['charset'],'UTF-8'),
		));
		exit;
	}
	list($ossClient,$oss_set) = oss_client($bucket['region']);
	if(!$ossClient->doesBucketExist($bucket['bucket'])){
		C::t('#bineoo_storage#bineoo_storage_bucket')->delete($bucket['bucket']);
		echo json_encode(array(
			'code'=>'error',
			'msg'=>diconv(lang('plugin/bineoo_storage', 'not_found_bucket_tips'),$_G['charset'],'UTF-8'),
		));
		exit;
	}
	if($ossClient->doesObjectExist($bucket['bucket'], $_GET['object'])){
		echo json_encode(array(
			'code'=>'succeed',
			'msg'=>diconv(lang('plugin/bineoo_storage', 'exist'),$_G['charset'],'UTF-8'),
		));
		exit;
	}
	echo json_encode(array(
		'code'=>'error',
		'msg'=>diconv(lang('plugin/bineoo_storage', 'not_exist'),$_G['charset'],'UTF-8'),
	));
	exit;

}else if($_GET['mod'] == 'local_exist'){
	if(is_file(DISCUZ_ROOT.'./'.$_GET['object']) || is_dir(DISCUZ_ROOT.'./'.$_GET['object'])){
		echo json_encode(array(
			'code'=>'succeed',
			'msg'=>diconv(lang('plugin/bineoo_storage', 'exist'),$_G['charset'],'UTF-8'),
		));
		exit;
	}
	echo json_encode(array(
		'code'=>'error',
		'msg'=>diconv(lang('plugin/bineoo_storage', 'not_exist'),$_G['charset'],'UTF-8'),
	));
	exit;
}else if($_GET['mod'] == 'list_object'){
}else if($_GET['mod'] == 'cache'){

	$lock = DISCUZ_ROOT.'./data/template/bineoo_cache.php';
	if(is_file($lock)){
		@unlink($lock);
	}
	bineoo_cache();
	sshowmessage(lang('plugin/bineoo_storage', 'update_cache_succeed'), oss_url(), array(), array('alert'=>'right','showdialog' => 1,'locationtime' => 2));
}

include template('bineoo_storage:manage');