<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class api_paybase{
	private $extend=null;
	protected function getextend(){
		if(preg_match('/^(\w+)\_(\w+)$/',get_class($this),$a))
			return $a[1];
	}
	protected function getconfig(){
		global $_G;
		if(empty($this->extend))
			$this->extend=$this->getextend();
		$str = @include DISCUZ_ROOT.'/source/plugin/dc_pay/data/'.$this->extend.'.config.php';
		$d = dunserialize(authcode($str['data'], 'DECODE', $_G['config']['security']['authkey']));
		return $d;
	}
	protected function saveconfig($arr){
		global $_G;
		if(empty($this->extend))
			$this->extend=$this->getextend();
		$str = serialize($arr);
		$d['data'] = authcode($str, 'ENCODE', $_G['config']['security']['authkey']);
		$configdata = 'return '.var_export($d, true).";\n\n";
		if($fp = @fopen(DISCUZ_ROOT.'/source/plugin/dc_pay/data/'.$this->extend.'.config.php', 'wb')) {
			fwrite($fp, "<?php\n//plugin dc_pay config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
			fclose($fp);
			return true;
		}
		return false;
	}
	protected function clearconfig(){
		if(empty($this->extend))
			$this->extend=$this->getextend();
		//$configdata = 'return '.var_export($arr, true).";\n\n";
		$file = DISCUZ_ROOT.'/source/plugin/dc_pay/data/'.$this->extend.'.config.php';
		@unlink($file);
		return !file_exists($file);
	}
}
?>