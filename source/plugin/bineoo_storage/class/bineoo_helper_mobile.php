<?php
/**
 *  Version: 1.0
 *  Date: 2017-03-25 15:27
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class bineoo_helper_mobile {


	public static function mobileoutput() {
		global $_G;
		if(!defined('TPL_DEFAULT')) {
			$content = ob_get_contents();
			ob_end_clean();
			$content = bineoo_replace_content($content);
			/*$content = preg_replace_callback('/<a.*?href=["|\']([^"]*)["|\'][^>]*>/i', function($data){
				if(stripos($data[0], 'bineoo-storage') === false){
					if(strpos($data[0], '?') === false) {
						$data[0] = str_replace($data[1], $data[1].'?mobile='.IN_MOBILE, $data[0]);
					} else {
						$data[0] = str_replace($data[1], $data[1].'&amp;mobile='.IN_MOBILE, $data[0]);
					}
					return $data[0];
				}else{
					return $data[0];
				}
			}, $content);*/
			ob_start();
			/*if('utf-8' != CHARSET) {
				$content = diconv($content, CHARSET, 'utf-8');
			}*/
			if(!$_G['bineoo_storage_output']){
				$_G['bineoo_storage_output'] = 1;
				$content = '<?xml version="1.0" encoding="utf-8"?>'.$content;
				if(IN_MOBILE === '3') {
					header("Content-type: text/vnd.wap.wml; charset=utf-8");
				} else {
					@header('Content-Type: text/html; charset=utf-8');
				}
			}
			echo $content;

		} elseif (defined('TPL_DEFAULT') && !$_G['cookie']['dismobilemessage'] && $_G['mobile']) {
			ob_end_clean();
			ob_start();
			$_G['forcemobilemessage'] = true;
			$query_sting_tmp = str_replace(array('&mobile=yes', 'mobile=yes'), array(''), $_SERVER['QUERY_STRING']);
			$_G['setting']['mobile']['pageurl'] = $_G['siteurl'].substr($_G['PHP_SELF'], 1).($query_sting_tmp ? '?'.$query_sting_tmp.'&mobile=no' : '?mobile=no' );
			unset($query_sting_tmp);
			dsetcookie('dismobilemessage', '1', 3600);
			showmessage('not_in_mobile');
			exit;
		}
	}
}

?>