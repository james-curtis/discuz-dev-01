<?php

/**
 * Copyright 2001-2099 1314ѧϰ��.
 * This is NOT a freeware, use is subject to license terms
 * $Id: hook.class.php 605 2018-08-19 15:10:06Z zhuge $
 * Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
 * Ӧ����ǰ��ѯ��QQ 15326940
 * Ӧ�ö��ƿ�����QQ 643306797
 * �����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
 * δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��
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

//Copyright 2001-2099 1314ѧϰ��.
//This is NOT a freeware, use is subject to license terms
//$Id: hook.class.php 1050 2018-08-19 07:10:06Z zhuge $
//Ӧ���ۺ����⣺http://www.1314study.com/services.php?mod=issue
//Ӧ����ǰ��ѯ��QQ 15326940
//Ӧ�ö��ƿ�����QQ 643306797
//�����Ϊ 1314ѧϰ����www.1314study.com�� ����������ԭ�����, ����ӵ�а�Ȩ��
//δ�������ù������ۡ�������ʹ�á��޸ģ����蹺������ϵ���ǻ����Ȩ��