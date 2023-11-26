<?php 
/**
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: phpdisk_del_process.inc.php 10 2014-08-02 14:34:18Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
*/
if(!defined('IN_DISCUZ')) {
	exit('[PHPDisk] Access Denied');
}
require 'includes/commons.inc.php';
login_auth($_G[uid]);
@set_time_limit(0);
@ignore_user_abort(true);

$param = trim($_GET[param]);
if(!$param){
	exit('alert("PHPDisk Delete Error!")');
}

$str = rawurldecode(pd_encode($param,'DECODE'));

if($str){

	parse_str($str);
	if($adminid<>1){
		exit('alert("PHPDisk Error Power!")');
	}
	$arr = explode('.',$pp);
	$thumb_file = $arr[0].'_thumb.'.$arr[1];
	$out_txt = lang('plugin/phpdisk_mini','del file')."【{$file_name}】，".lang('plugin/phpdisk_mini','file id').":[{$file_id}]，";
	//exit(PHPDISK_ROOT.$pp);
	if($task=='safe_del'){
		if(file_exists(PHPDISK_ROOT.$pp)){
			if(@unlink(PHPDISK_ROOT.$pp)){
				@unlink(PHPDISK_ROOT.$thumb_file);
				DB::query("delete from phpdisk_mini_files where file_id='$file_id' and is_del=1");
				echo 'document.writeln("'.$out_txt.' <span class=\"txtblue\">'.lang('plugin/phpdisk_mini','success').'</span><br>");'.LF;
			}else{
				echo 'document.writeln("'.$out_txt.' <span class=\"txtred\">'.lang('plugin/phpdisk_mini','fail').'</span><br><br>");'.LF;
				echo 'document.writeln("<span class=\"txtred\">'.lang('plugin/phpdisk_mini','del file fail').'</span>，'.lang('plugin/phpdisk_mini','del file error tips').'<br><br> '.lang('plugin/phpdisk_mini','file log in').': <span class=\"txtblue\">system/delfile_log.php</span>");'.LF;
				$log = '<?php exit; ?> '.lang('plugin/phpdisk_mini','safe del fail').$out_txt.LF.lang('plugin/phpdisk_mini','path').':'.PHPDISK_ROOT.$pp.' '.lang('plugin/phpdisk_mini','time').':'.date('Y-m-d H:i:s').LF;
				write_file(PHPDISK_ROOT.'system/delfile_log.php',$log,'ab');
			}
		}else{
			echo 'document.writeln("'.$out_txt.' <span class=\"txtred\">'.lang('plugin/phpdisk_mini','fail').'</span><br><br>");'.LF;
			echo 'document.writeln("<span class=\"txtred\">'.lang('plugin/phpdisk_mini','del file fail').'</span>'.lang('plugin/phpdisk_mini','file not exists').'<br><br> '.lang('plugin/phpdisk_mini','file log in').': <span class=\"txtblue\">system/delfile_log.php</span>");'.LF;
			$log = '<?php exit; ?> ['.lang('plugin/phpdisk_mini','file not exists').']'.$out_txt.LF.lang('plugin/phpdisk_mini','path').':'.PHPDISK_ROOT.$pp.' '.lang('plugin/phpdisk_mini','time').':'.date('Y-m-d H:i:s').LF;
			write_file(PHPDISK_ROOT.'system/delfile_log.php',$log,'ab');
		}
	}elseif($task=='nosafe_del'){
		@unlink(PHPDISK_ROOT.$pp);
		@unlink(PHPDISK_ROOT.$thumb_file);
		DB::query("delete from phpdisk_mini_files where file_id='$file_id' and is_del=1");
		echo 'document.writeln("'.$out_txt.' <span class=\"txtblue\">'.lang('plugin/phpdisk_mini','success').'</span><br>");'.LF;
	}else{
		echo 'document.writeln("'.lang('plugin/phpdisk_mini','param error').'");'.LF;
	}

}else{
	exit('<br> <p style="font-size:14px" align="center">Program is running, but error params!</p>');
}

?>
