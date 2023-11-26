<?php
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';

class xhw6
{
    protected $pay;

    public function __construct()
    {
        $this->pay = new PayMent('keke_group');
    }

    public function donotify($orderid,$tradeno,$param,$uid,$username)
    {
        require_once DISCUZ_ROOT.'./source/plugin/keke_group/common.php';
        _upuserdata($orderid,$tradeno);
    }

    public function doreturn($orderid,$param,$uid,$username)
    {
        return;
    }

    public function setOrder($price,$subject,$type,$orderid)
    {
        $this->pay->SetPayType($type);
        return $this->pay->SetOrder($orderid,$price,$subject);
    }

    public function goPay()
    {
        header('Location: '.array_values($this->pay->GetPayUrl())[0]);
    }

    public function getPayType()
    {
        return $this->pay->GetPayType();
    }

    public function getAllPayInfo()
    {
        return $this->pay->getAllPayInfo();
    }
}