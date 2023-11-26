<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 605 2018-08-19 15:10:06Z zhuge $
 * 应用售后问题：http://www.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */
if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}
class plugin_study_attachmentad {
}
class plugin_study_attachmentad_forum extends plugin_study_attachmentad{
	function viewthread_posttop_output(){
			global $_G, $postlist;
			$S_a = $_G['cache']['plugin']['study_attachmentad'];
			$study_fids = unserialize($S_a['study_fids']);
			if(in_array($_G[fid], $study_fids)){
				require_once libfile('function/core', 'plugin/study_attachmentad/source');
				foreach($postlist as $id => $post) {
						$postlist[$id] = study_attachmentad_ad($post);
				}
			}
			return array();
	}
}

//Copyright 2001-2099 1314学习网.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 1050 2018-08-19 07:10:06Z zhuge $
//应用售后问题：http://www.1314study.com/services.php?mod=issue
//应用售前咨询：QQ 15326940
//应用定制开发：QQ 643306797
//本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
//未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。