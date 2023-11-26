<?php
class plugin_top_fjm {

	function common(){
        global $_G;
        loadcache('bineoo_storage_setting');
        $oss_set = dunserialize($_G['cache']['bineoo_storage_setting'])['Access_Key_ID'];
		if(CURSCRIPT == 'forum' && CURMODULE == 'attachment' && empty($oss_set)){

			global $_G;

			$top_fjm = $_G['cache']['plugin']['top_fjm'];

			$top_fids = (array)unserialize($top_fjm['top_fids']);

			$top_gids = (array)unserialize($top_fjm['top_gids']);



			if(in_array($_G['groupid'],$top_gids)){

				include_once DISCUZ_ROOT.'/source/plugin/top_fjm/forum_attachment.php';

				exit();

			}

		}

	}

}





?>