<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2018/8/18
 * Time: 20:36
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
class yyhyotenpay_pay extends api_pay {
    /**
     * @var mixed 商户配置
     */
    protected $config;
    protected $gateway;
    /**
     * @var 本次交易信息
     */
    protected $parameter;
    public function __construct() {
        global $_G;
        parent::__construct();
        $this->config = $this->getconfig();
        $this->gateway = $this->config['apiurl'];
        require_once DISCUZ_ROOT.'./source/plugin/dc_pay/lib/yyhyo/epay_submit.class.php';
    }

    public function setorder($orderid, $price, $subject, $body, $showurl)
    {
        global $_G;
        $notify_url = 'http://pay.xhw6.cn/yyhyotenpay/yyhyotenpay_notify.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = 'http://pay.xhw6.cn/yyhyotenpay/yyhyotenpay_return.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //商户订单号
        $out_trade_no = $orderid;
        //商户网站订单系统中唯一订单号，必填

        //支付方式
        $type = 'tenpay';
        //商品名称
        $name = $subject;
        //付款金额
        $money = $price;
        //站点名称
        $sitename = $_G['setting']['sitename'];

        global $parameter;
        $parameter = array(
            "pid" => trim($this->config['partner']),
            "type" => $type,
            "notify_url"	=> $notify_url,
            "return_url"	=> $return_url,
            "out_trade_no"	=> $out_trade_no,
            "name"	=> $name,
            "money"	=> $money,
            "sitename"	=> $sitename
        );
        /*
         * array (
                'money' => '0.01',
                'name' => '积分充值0.01元钻石1',
                'out_trade_no' => '201808191657148307420681',
                'notify_url' => 'http://pay.xhw6.cn/yyhyotenpay/yyhyotenpay_notify.php',
                'pid' => '4238',
                'type' => 'alipay',
                'return_url' => 'http://pay.xhw6.cn/yyhyotenpay/yyhyotenpay_return.php',
                'sitename' => '讯幻网',
            );
         */
    }

    public function create_payurl()
    {
        //建立请求
        global $parameter;
        $alipaySubmit = new AlipaySubmit($this->config);
        return $alipaySubmit->buildRequestForm($parameter);
    }

    public function getpayinfo(){
        define('TRADENO', $_GET['trade_no']?$_GET['trade_no']:$_POST['trade_no']);
        define('ORDERID', $_GET['out_trade_no']?$_GET['out_trade_no']:$_POST['out_trade_no']);
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

    public function tradecheck()
    {
        global $parameter;
        require_once DISCUZ_ROOT.'./source/plugin/dc_pay/lib/yyhyo/epay_notify.class.php';
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->config);
        $verify_result = $alipayNotify->verifyNotify();
        //file_put_contents($verify_result?'1':'0','');
        if ($verify_result) {
            return true;
        } else {
            return false;
        }
    }

    public function pricecheck($price){
        $nprice = $_GET['money'];
        if(floatval($price) == floatval($nprice))return true;
    }

}

class yyhyotenpay_mobilepay extends yyhyotenpay_pay
{

}