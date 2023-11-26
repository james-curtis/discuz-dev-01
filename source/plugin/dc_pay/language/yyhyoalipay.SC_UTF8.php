<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
return array(
    'install_title'=>'烟雨云支付宝',
    'install_des'=>'烟雨云支付宝付款',
    'install_author'=>'多维者工作室(www.duoweizhe.top)&讯幻网(www.xhw6.cn)',
    'partner'=>'商户ID',
    'key'=>'商户KEY',
    'transport'=>'访问模式',
    'transport_msg'=>'根据自己的服务器是否支持ssl访问，若支持请填写https；若不支持请填写http',
    'apiurl'=>'支付API地址',
    'sign_type'=>'签名方式',
    'input_charset'=>'字符编码格式',
    'input_charset_msg'=>'目前支持 gbk 或 utf-8',/*
    'memcp_credits_addfunds_caculate_radio' => '人民币 <span id="desamount">5</span> 元',
    'memcp_credits_addfunds_rules_max' => '单次最高充值',
    'memcp_credits_addfunds_rules_min' => '单次最低充值',
    'memcp_credits_addfunds_rules_month' => '最近 30 天最高充值',
    'memcp_credits_addfunds_rules_ratio' => '人民币现金 <strong style="color: red;">1</strong> 元',
    'credits_need' => '所需',*/
);
?>