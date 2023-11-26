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

/**
 * 电脑端
 * 烟雨云阿里支付
 * Class yyhyoalipay_pay
 */
class yyhyoalipay_pay extends api_pay {
    /**
     * @var mixed 商户配置
     */
    protected $config;

    /**
     * @var 本次交易信息
     */
    protected $parameter;

    /**
     * yyhyoalipay_pay constructor.
     */
    public function __construct() {
        global $_G;
        parent::__construct();
        $this->config = $this->getconfig();
        $this->gateway = $this->config['apiurl'];
        require_once DISCUZ_ROOT.'./source/plugin/dc_pay/lib/yyhyo/epay_submit.class.php';
    }

    /**
     * 设置订单参数
     * @param $orderid 订单ID
     * @param $price 价格
     * @param $subject 标题
     * @param $body 内容 可选
     * @param $showurl 商品链接 可选
     */
    public function setorder($orderid, $price, $subject, $body, $showurl)
    {
        global $_G;
        $notify_url = 'http://pay.xhw6.cn/yyhyoalipay/yyhyoalipay_notify.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数
        //页面跳转同步通知页面路径
        $return_url = 'http://pay.xhw6.cn/yyhyoalipay/yyhyoalipay_return.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
        //商户订单号
        $out_trade_no = $orderid;
        //商户网站订单系统中唯一订单号，必填

        //支付方式
        $type = 'alipay';
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


        /*示例
         * array (
                'money' => '0.01',
                'name' => '积分充值0.01元钻石1',
                'out_trade_no' => '201808191657148307420681',
                'notify_url' => 'http://pay.xhw6.cn/yyhyoalipay/yyhyoalipay_notify.php',
                'pid' => '4238',
                'type' => 'alipay',
                'return_url' => 'http://pay.xhw6.cn/yyhyoalipay/yyhyoalipay_return.php',
                'sitename' => '讯幻网',
            );
         */
    }

    /**
     * 创建支付链接或者POST表单
     * @return string 提交表单HTML文本
     */
    public function create_payurl()
    {
        //建立请求
        global $parameter;
        $alipaySubmit = new AlipaySubmit($this->config);
        return $alipaySubmit->buildRequestForm($parameter);
    }

    /**
     * 回调
     * 获取支付信息，就是订单号和交易号
     * @return array 下面将要输出的成功消息
     */
    public function getpayinfo(){
        define('TRADENO', $_GET['trade_no']?$_GET['trade_no']:$_POST['trade_no']);
        define('ORDERID', $_GET['out_trade_no']?$_GET['out_trade_no']:$_POST['out_trade_no']);
        return array(
            'succeed'=>'success',
            'fail'=>''
        );
    }

    /**
     * 回调确认
     * @return bool
     */
    public function notifycheck(){
        if(!empty($_GET)&&$this->_tradecheck()){
            if(in_array($_GET['trade_status'],array('TRADE_FINISHED','TRADE_SUCCESS')))
                return true;
        }
        return false;
    }

    /**
     * 自定义方法
     * 交易确认
     * @return bool
     */
    public function _tradecheck()
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

    /**
     * 回调
     * 确认价格
     * @param $price
     * @return bool
     */
    public function pricecheck($price){
        $nprice = $_GET['money'];
        if(floatval($price) == floatval($nprice))return true;
    }

}

/**
 * 手机端
 * 烟雨云阿里支付
 * Class yyhyoalipay_mobilepay
 */
class yyhyoalipay_mobilepay extends yyhyoalipay_pay
{

}