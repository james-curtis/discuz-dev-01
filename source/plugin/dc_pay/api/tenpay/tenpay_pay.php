<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class tenpay_pay extends api_pay{
	private $config;
	private $gateway = 'https://gw.tenpay.com/gateway/pay.htm';
	public function __construct() {
		parent::__construct();
		$this->config = $this->getconfig();
	}
	public function setorder($orderid, $price, $subject, $body, $showurl){
		global $_G;
		$this->_args = array(
			'partner' 		=> $this->config['partner'],
			'input_charset' 	=> strtoupper(CHARSET),
			'notify_url' 		=> $_G['siteurl'].'source/plugin/dc_pay/api/tenpay/tenpay_notify.php',
			'return_url' 		=> $_G['siteurl'].'source/plugin/dc_pay/api/tenpay/tenpay_return.php',
			'out_trade_no' 		=> $orderid,
			'subject' 		=> $subject,
			'body'       =>$body?$body:$subject,
			'total_fee'  =>$price*100,
			'spbill_create_ip'   =>$_G['clientip'],
			'fee_type'    =>1,
			'sign_type' =>'MD5',
			'service_version' =>'1.0',
			'sign_key_index'  =>1,
			'attach'    =>'dc_pay',
			'time_start'   =>dgmdate(TIMESTAMP, 'YmdHis'),
			'trade_mode' => $this->config['type'],
			'trans_type'  =>1,
		);
	}
	public function create_payurl(){
		$this->MD5sign();
		return array(
			'gateway'=>$this->gateway,
			'args'=>$this->_args,
		);
	}
	public function getpayinfo(){
		define('TRADENO', $_GET['transaction_id']);
		define('ORDERID', $_GET['out_trade_no']);
		return array(
			'succeed'=>'success',
			'fail'=>'fail'
		);
	}
	public function notifycheck(){
		if(!empty($_GET)){
			return $this->tradecheck($_GET);
		}
		return false;
	}
	private function tradecheck($notify){
		$notifycheck = array(
			'partner'=>$this->config['partner'],
			'notify_id'=>$notify['notify_id'],
		);
		$nsign = $nurl = '';
		ksort($notifycheck);
		foreach($notifycheck as $k => $v) {
			$nsign .= $k . "=" . $v . "&";
			$nurl .= $k . "=" . $v . "&";
		}
		$nsign .= "key=" . $this->config['key'];
		$sign = strtoupper(md5($nsign));
		$data = dfsockopen('https://gw.tenpay.com/gateway/simpleverifynotifyid.xml?'.$nurl.'sign='.$sign);
		if(empty($data)){
			return false;
		}
		$xml = simplexml_load_string($data);
		if($xml->retcode != '0')
			return false;
		ksort($notify);
		foreach($notify as $k => $v) {
			if("sign" !== $k && "" !== $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars .= "key=" . $this->config['key'];
		$sign = strtoupper(md5($signPars));
		$tenpaySign = strtoupper($notify["sign"]);
		if($sign != $tenpaySign)
			return false;
		
		if($notify['trade_state'] == '0'){
			return true;
		}
	}
	private function MD5sign(){
		ksort($this->_args);
		$sign = '';
		foreach($this->_args as $key => $val) {
			if($val!=='' && "sign" !== $key)
				$sign .= $key.'='.$val.'&';
		}
		$sign .= "key=" .$this->config['key'];
		$sign = strtoupper(md5($sign));
		$this->_args['sign'] = $sign;
	}
	public function pricecheck($price){
		$nprice = $_GET['total_fee'] / 100;
		if(floatval($price) == floatval($nprice))return true;
	}
}
?>