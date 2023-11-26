<?php

function check_interface($identifier)
{
    $data = C::t('#dc_pay#dc_pay_api')->range();
    $install = false;
    foreach ($data as $v)
    {
        if ($v['plugin'] == $identifier)$install =true;
    }
    return $install;
}

function check_interface_and_install($identifier,$phr=array())
{
    if (!check_interface($identifier))
    {
        require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';
        $phr_ = array(
            'plugin'=>$identifier, //插件标识符
            'include'=>'xhw6.class.php', //注册类文件
            'class'=>'xhw6', //注册类名称
            'return'=>'doreturn', //注册页面跳转同步通知方法
            'notify'=>'donotify', //注册服务器异步通知方法
            'payok'=>null
        );
        $in = array(
            'plugin'=>$phr['plugin']?$phr['plugin']:$phr_['plugin'],
            'include'=>$phr['include']?$phr['include']:$phr_['include'],
            'class'=>$phr['class']?$phr['class']:$phr_['class'],
            'return'=>$phr['return']?$phr['return']:$phr_['return'],
            'notify'=>$phr['notify']?$phr['notify']:$phr_['notify'],
            'payok'=>$phr['payok']?$phr['payok']:$phr_['payok']
        );
        //print_r($in);exit;
        return PayHook::Register($in); //注册
    }
}