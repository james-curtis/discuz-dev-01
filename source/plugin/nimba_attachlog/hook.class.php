<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nimba_attachlog{
}
class plugin_nimba_attachlog_forum extends plugin_nimba_attachlog{
	function _attach_ment_log(){
		loadcache('plugin');
		global $_G;	
		$open=$_G['cache']['plugin']['nimba_attachlog']['open'];
		if($open){
            loadcache('bineoo_storage_setting');
            $oss_set = dunserialize($_G['cache']['bineoo_storage_setting'])['Access_Key_ID'];
			if ($oss_set)
            {
                $data[0] = $_GET['aid'];
                $data[1] = $_GET['k'];
                $data[2] = $_GET['t'];
                $data[3] = $_GET['uid'];
                $data[4] = $_GET['tableid'];
            }
            else{
                $data=explode('|',base64_decode($_GET['aid']));
            }
			$data[0]=intval($data[0]);
			$uid=$_G['uid'];
			if($data[0]&&$data[3]==$uid){//0:aid 1:hash 2:dateline 3:uid 4:tid
				if($uid){//会员 同uid只记录一次
					$num=C::t('#nimba_attachlog#nimba_attachlog')->count_by_aid_uid($data[0],$uid);
					if(!$num){
						$attach=array('aid'=>$data[0],'uid'=>$data[3],'tid'=>$data[4],'ip'=>$_G['clientip'],'dateline'=>$data[2]);
						C::t('#nimba_attachlog#nimba_attachlog')->insert($attach);
					}
				}else{//游客 同IP只记录一次
					$num=C::t('#nimba_attachlog#nimba_attachlog')->count_by_aid_ip($data[0],$_G['clientip']);
					if(!$num){
						$attach=array('aid'=>$data[0],'uid'=>$data[3],'tid'=>$data[4],'ip'=>$_G['clientip'],'dateline'=>$data[2]);
						C::t('#nimba_attachlog#nimba_attachlog')->insert($attach);
					}				
				}
			}	
		}
	}
}
class plugin_nimba_attachlog_group extends plugin_nimba_attachlog_forum{

}
?>