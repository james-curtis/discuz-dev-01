<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2018/8/20
 * Time: 18:33
 */

$scriptlang['xhw6_vip'] = array(
    'pay_sucess' => '支付成功,正在为您跳转！',
    'day' => '天',
    'tips' => '<li>欢迎使用 [讯幻网] 购买用户组</li><li>您可以在这里查看用户购买记录！</li>',
    'tableheader' => '用户购买订单搜索',
    'order_status' => '订单状态',
    'order_status_all' => '所有状态',
    'pay_sucess' => '支付成功,正在为您跳转！',
    'order_status_sucess' => '付款成功',
    'order_status_wait' => '等待付款',
    'username' => '付款人',
    'order_submitdate' => '订单提交时间',
    'order_confirmdate' => '订单付款时间',
    'order_id' => '订单号',
    'order_trade_no' => '交易号',
    'order_group_name' => '购买用户组',
    'order_valitidy' => '有效期',
    'order_price' => '支付金额',
    'moni_data' => '后台模拟购买数据',
    'user_id' => '用户ID',
    'group_id' => '用户组ID',
    'tishi_info' => '数据插入成功，将在前台显示，管理员注意插入数据与正式数据的差别!',
    'extcreditstitle' => '威望:200,金钱:200',
    'extcredits' => 'extcredits1:100,extcredits2:100',
    'skip_info' => '正在跳转中......',
    'validityinfo' => '永久',
    'rulenote02' => '<b>配置说明，配置各项以"||"号分隔，可以设置多个用户组，每行一个。格式如下：</b>
<br/>
用户组名称（可以自己定义）|| 对应的用户组id（即 groupid 可以在后台>用户>用户组中查看 ）|| 价格 ||有效期（永久有效请填写0）||
赠送积分title（填写格式 积分名称:赠送数量,积分名称:赠送数量）||赠送积分(填写格式积分标识:赠送数量,积分标识:赠送数量（赠送请多个以逗号隔开,积分名称和积分标识可以在 后台>全局>积分设置中查看)||前台页面是否显示促销icon(hot:不显示，NOhot:显示)。
<br/>
<font color=red>以上如果配置有错误，会导致问题。如果遇到配置问题可联系QQ:154606914,寻求支持！</font><br/>',
    'rulenote03' => '<b>配置示例 --以下为正式配置 用户请根据自己的实际情况修改:</b>
<br/>
铜牌会员||21||0.01||365||威望:100,金钱:100||extcredits1:100,extcredits2:100||hot<br/>
银牌会员||21||0.02||365||威望:200,金钱:200||extcredits1:100,extcredits2:100||NOhot<br/>
金牌会员||21||0.03||365||威望:300,金钱:300||extcredits1:100,extcredits2:100||hot<br/>',
    'rulenote01' => '<font color=red><b>使用前请确认，在后台已正确配置支付宝的合作者身份 (PID)、交易安全校验码 (key)（运营->电子商务->支付宝）,配置错误将无法正确支付。</b></font>',
    'rulenote00' => '<b>使用前请仔细阅读如下规则:</b>',
    'ruletitle' => '使用规则',
    'eccontractinfo' => '请检查您在后台已正确配置支付宝的合作者身份 (PID)、交易安全校验码 (key)（运营->电子商务->支付宝）',
    'danwei' => '天',
);

$templatelang['xhw6_vip'] = array(
    'buyGroup' => '购买VIP用户组',
    'openGroup' => '开通VIP会员',
    'group_column01' => '用户组',
    'group_column02' => '优惠价格',
    'group_column03' => '有效期限',
    'group_column04' => '赠送礼包',
    'group_column05' => '购买',
    'buyButton' => '立即购买',
    'danwei' => '天',
    'danwei01' => '元',
    'validityinfo' => '永久',
    'notice' => '注意事项!',
    'notice01' => '*付款时请务必选择即时到账方式（如图），否则会导致无法购买成功！',
    'userinfo' => '用户信息',
    'loginoutinfo' => '您需要登录后才可以购买用户组哟！',
    'notice02' => '开通了',
    'notice03' => '最新VIP会员',
    'notice04' => '当前用户组',
    'notice05' => '用户名',
    'loginM' => '登录',
    'registerM' => '注册',
    'closePage' => '关闭',
    'pay_type' => '请选择支付方式',
    'fukuai' => '去付款',
);

$installlang['xhw6_vip'] = array(
    'error_dc_pay' => '[DC] 通用支付 API 未安装',
);
























