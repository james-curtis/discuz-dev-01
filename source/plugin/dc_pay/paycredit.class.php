<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
class paycredit{
    protected $pay_ment;
    public function __construct()
    {
        $this->pay_ment = new PayMent('dc_pay');
    }

    public function donotify($orderid,$tradeno,$param,$uid,$username){
		$this->dodeal($orderid,$tradeno,$param,$uid,$username);
	}
	public function doreturn($orderid,$tradeno,$param,$uid,$username){
		global $_G;
		$this->dodeal($orderid,$tradeno,$param,$uid,$username);
	}
	private function dodeal($orderid,$tradeno,$param,$uid,$username){
		global $_G;
		if($_G['setting']['version']=='X2.5'){
			updatemembercount($uid, array($param['extcredit'] => $param['credit']), true, '', 0, '');
		}else{
			updatemembercount($uid, array($param['extcredit'] => $param['credit']), true, '', 0, '',lang('plugin/dc_pay','buycredit'),str_replace('{amount}',$param['amount'],lang('plugin/dc_pay','buycredit_msg')));
		}
	}

    public function getAllPayInfo()
    {
        return $this->pay_ment->getAllPayInfo();
    }
}
?>