<?php
require_once DISCUZ_ROOT.'./source/plugin/dc_pay/payment.lib.class.php';

class xhw6_vip
{
    protected $pay;

    public function __construct()
    {
        $this->pay = new PayMent('xhw6_vip');
    }

    public function donotify($orderid,$tradeno,$param,$uid,$username)
    {
        global $_G;
        $orderinfo = C::t('#xhw6_vip#xhw6_vip')->fetch_buy_order_id($orderid);
        $member=C::t('common_member')->fetch($orderinfo['uid'], false, 1);

        if(count($member)==0){
            $isinarchive = '_inarchive';
        }else{
            $isinarchive = '';
        }

        C::t('#xhw6_vip#xhw6_vip')->update_by_order_id($orderid,$tradeno,$_G['timestamp'] );

        //设置用户组的有效日期
        if($orderinfo['validity']==0){

            C::t('common_member'.$isinarchive)->update($orderinfo['uid'], array('groupid'=>$orderinfo['group_id']));

        }elseif (is_numeric($orderinfo['validity']) && $orderinfo['validity']>0) {

            $groupexpiryTime =strtotime(date('Y-m-d H:i:s',strtotime("+$orderinfo[validity] day")));


            //	echo $orderinfo['group_id']."-----".$groupexpiryTime;

            C::t('common_member'.$isinarchive)->update($orderinfo['uid'], array('groupid'=>$orderinfo['group_id'],'groupexpiry'=>$groupexpiryTime));

            $groupterms['main'] = array('time' => $groupexpiryTime);
            $groupterms['ext'][$orderinfo['group_id']] = $groupexpiryTime;

            C::t('common_member_field_forum'.$isinarchive)->update($orderinfo['uid'],array('groupterms' => serialize($groupterms)));
        }

        //根据规则送积分
        if($orderinfo['extcredits']!="" && $orderinfo['extcredits']!="0"){
            $tmpExt=explode(',', $orderinfo['extcredits']);

            foreach ($tmpExt as $value) {

                $extcredits=explode(':', $value);
                updatemembercount($orderinfo['uid'], array($extcredits[0] =>$extcredits[1]));

            }
        }
    }

    public function doreturn($orderid,$param,$uid,$username)
    {
        global $_G;
        showmessage('xhw6_vip:pay_sucess', $_G['siteurl'].'home.php?mod=spacecp&ac=usergroup');
    }

    public function setOrder($price,$subject,$type)
    {
        $this->pay->SetPayType($type);
        return $this->pay->SetOrder('',$price,$subject);
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