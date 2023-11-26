<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
C::import('api/paybase','plugin/dc_pay',false);
class api_pay extends api_paybase{
	protected $_lang = array();
	protected $_args = array();

    /**
     * 初始化
     * api_pay constructor.
     */
	public function __construct(){
		$this->_lang = @include DISCUZ_ROOT.'./source/plugin/dc_pay/language/'.$this->getextend().'.'.currentlang().'.php';
		if(empty($this->_lang))$this->_lang = @include DISCUZ_ROOT.'./source/plugin/dc_pay/language/'.$this->getextend().'.php';
	}

    /**
     * 设置订单参数
     * @param $orderid 订单ID
     * @param $price 价格
     * @param $subject 标题
     * @param $body 内容 可选
     * @param $showurl 商品链接 可选
     */
	public function setorder($orderid, $price, $subject, $body, $showurl){}

    /**
     * 创建订单链接
     */
	public function create_payurl(){}

    /**
     * 获取付款状态
     */
	public function getpayinfo(){}

    /**
     *异步通知状态检测
     */
	public function notifycheck(){}
}
?>