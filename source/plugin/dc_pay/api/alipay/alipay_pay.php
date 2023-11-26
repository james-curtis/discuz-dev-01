<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class alipay_pay extends api_pay{
	protected $config;
	protected $gateway = 'https://openapi.alipay.com/gateway.do';
	protected $ismobile = false;
	public function __construct() {
		parent::__construct();
		$this->config = $this->getconfig();
	}
	public function setorder($orderid, $price, $subject, $body, $showurl){
		global $_G;
		$this->_args = array(
			'app_id' 		=> $this->config['pid'],
			'method' 	=> 'alipay.trade.page.pay',
			'format'   =>'JSON',
			'charset'   => CHARSET,
			'notify_url' 		=> $_G['siteurl'].'source/plugin/dc_pay/api/alipay/alipay_notify.php',
			'return_url' 		=> $_G['siteurl'].'source/plugin/dc_pay/api/alipay/alipay_return.php',
			'sign_type'     => 'RSA2',
			'timestamp'    => dgmdate(TIMESTAMP,'Y-m-d H:i:s'),
			'version'=>'1.0',
		);
		$this->_args['biz_content'] = '{"out_trade_no":"'.$orderid.'","product_code":"FAST_INSTANT_TRADE_PAY","total_amount":"'.sprintf('%.2f',$price).'","subject":"'.$subject.'","timeout_express":"30m"';
		if($this->config['qrcode'])
			$this->_args['biz_content'] .=',"qr_pay_mode":"4","qrcode_width":"200"';
		$this->_args['biz_content'] .='}';
	}
	public function create_payurl(){
		$this->RSA2sign();
		if($this->config['qrcode']&&!$this->ismobile){
			$urlstr = '';
			foreach($this->_args as $key => $val) {
				$urlstr .= $key.'='.rawurlencode($val).'&';
			}
			$urlstr = substr($urlstr, 0,-1);
			return array(
				'type'=>'qrcode',
				'qrcode'=>$this->gateway.'?'.$urlstr,
				'isframe'=>1,
			);
		}
		return array(
			'gateway'=>$this->gateway,
			'args'=>$this->_args,
		);
	}
	public function getpayinfo(){
		define('TRADENO', $_GET['trade_no']);
		define('ORDERID', $_GET['out_trade_no']);
		return array(
			'succeed'=>'success',
			'fail'=>''
		);
	}
	public function notifycheck(){
		if(!empty($_GET)&&$this->tradecheck($_GET)){
			if(in_array($_GET['trade_status'],array('TRADE_FINISHED','TRADE_SUCCESS')))
				return true;
		}
		return false;
	}
	protected function tradecheck($notify){
		if(!$this->config['key']) {
			return false;
		}
		ksort($notify);
		$data = '';
		foreach($notify as $key => $val){
			if($key != 'sign' && $key != 'sign_type') $data .= "&$key=$val";
		}
		$data = substr($data,1);
		$pubKey= $this->config['pubkey'];
		$res = "-----BEGIN PUBLIC KEY-----\n" .
			wordwrap($pubKey, 64, "\n", true) .
			"\n-----END PUBLIC KEY-----";
		$result = (bool)openssl_verify($data, base64_decode($notify['sign']), $res, OPENSSL_ALGO_SHA256);
		return $result;
	}

	protected function RSA2sign(){
		ksort($this->_args);
		$data = '';
		foreach($this->_args as $key => $val) {
			$data .= '&'.$key.'='.$val;
		}
		$data = substr($data, 1);
		$priKey = $this->config['key'];
		$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
			wordwrap($priKey, 64, "\n", true) .
			"\n-----END RSA PRIVATE KEY-----";
		openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		$this->_args['sign'] = base64_encode($sign);
	}
	public function pricecheck($price){
		$nprice = $_GET['total_amount'];
		if(floatval($price) == floatval($nprice))return true;
	}
}
class alipay_mobilepay extends alipay_pay{
	public function __construct() {
		parent::__construct();
		$this->ismobile = true;
	}
	public function setorder($orderid, $price, $subject, $body, $showurl){
		global $_G;
		parent::setorder($orderid, $price, $subject, $body, $showurl);
		$this->_args['method'] = 'alipay.trade.wap.pay';
		$this->_args['biz_content'] = '{"out_trade_no":"'.$orderid.'","product_code":"QUICK_WAP_WAY","total_amount":"'.sprintf('%.2f',$price).'","subject":"'.$subject.'","timeout_express":"30m"}';
	}
}
?>