<?php
/**
 * 自助开通分站
**/
include("../includes/common.php");
if($islogin2==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}elseif($conf['fenzhan_buy']==0){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('当前站点未开启自助开通分站功能！');window.location.href='./';</script>");
}
if($_GET['act']=='check'){
	$qz = daddslashes($_GET['qz']);
	$domain = $qz . '.' . daddslashes($_GET['domain']);
	$srow=$DB->get_row("SELECT * FROM shua_site WHERE domain='{$domain}' or domain2='{$domain}' limit 1");
	if($srow)exit('1');
	else exit('0');
}elseif($_GET['act']=='check2'){
	$user = daddslashes($_GET['user']);
	$srow=$DB->get_row("SELECT * FROM shua_site WHERE user='{$user}' limit 1");
	if($srow)exit('1');
	else exit('0');
}elseif($_GET['act']=='pay'){
	$qz = strtolower(daddslashes($_POST['qz']));
	$domain = strtolower(strip_tags(daddslashes($_POST['domain'])));
	$user = strip_tags(daddslashes($_POST['user']));
	$pwd = strip_tags(daddslashes($_POST['pwd']));
	$name = strip_tags(daddslashes($_POST['name']));
	$qq = daddslashes($_POST['qq']);
	$domain = $qz . '.' . $domain;
	if (strlen($qz) < 2 || strlen($qz) > 10 || !preg_match('/^[a-z0-9\-]+$/',$qz)) {
		exit('{"code":-1,"msg":"域名前缀不合格！"}');
	} elseif (!preg_match('/^[a-zA-Z0-9]+$/',$user)) {
		exit('{"code":-1,"msg":"用户名只能为英文或数字！"}');
	} elseif ($DB->get_row("SELECT * FROM shua_site WHERE user='{$user}' limit 1")) {
		exit('{"code":-1,"msg":"用户名已存在！"}');
	} elseif (strlen($pwd) < 6) {
		exit('{"code":-1,"msg":"密码不能低于6位"}');
	} elseif (strlen($name) < 2) {
		exit('{"code":-1,"msg":"网站名称太短！"}');
	} elseif (strlen($qq) < 5 || !preg_match('/^[0-9]+$/',$qq)) {
		exit('{"code":-1,"msg":"QQ格式不正确！"}');
	} elseif ($DB->get_row("SELECT * FROM shua_site WHERE domain='{$domain}' or domain2='{$domain}' limit 1") || $qz=='www' || $domain==$_SERVER['HTTP_HOST'] || in_array($domain,explode('|',$conf['fenzhan_remain']))) {
		exit('{"code":-1,"msg":"此前缀已被使用！"}');
	}
	$endtime = date("Y-m-d H:i:s", strtotime("+ 12 months", time()));
	$trade_no=date("YmdHis").rand(111,999);
	$input=$domain.'|'.$user.'|'.$pwd.'|'.$name.'|'.$qq.'|'.$endtime;
	$need=$conf['fenzhan_price'];
	$sql="insert into `shua_pay` (`trade_no`,`tid`,`zid`,`input`,`num`,`name`,`money`,`ip`,`userid`,`addtime`,`status`) values ('".$trade_no."','-2','".($siterow['zid']?$siterow['zid']:1)."','".$input."','1','自助开通分站','".$need."','".$clientip."','".$cookiesid."','".$date."','0')";
	if($DB->query($sql)){
		exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","need":"'.$need.'"}');
	}else{
		exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
	}
}
$title='自助开通分站';
include './head.php';
?>
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
</style>
  <nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">导航按钮</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="./">自助下单系统管理中心</a>
      </div><!-- /.navbar-header -->
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li>
            <a href="./login.php"><span class="glyphicon glyphicon-user"></span> 登陆</a>
          </li>
		  <li class="active"><a href="./reg.php"><span class="glyphicon glyphicon-globe"></span> 自助开通</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->
  <div style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-md-8 center-block" style="float: none;">
		<div class="alert alert-success">
            分站系统新上线，首开限时优惠中，仅需<b><?php echo $conf['fenzhan_price']?></b>元即可开通！<?php if($conf['fenzhan_free']){?>开通后赠送<b><?php echo $conf['fenzhan_free']?></b>元余额！<?php }?>
        </div>
        <div class="alert alert-info">点击下方按钮系统全自动为你开通分站，无需手工搭建，更不需要建站技术即可拥有自己的平台。</div>

<?php
$domains=explode(',',$conf['fenzhan_domain']);
$select='';
foreach($domains as $domain){
	$select.='<option value="'.$domain.'">'.$domain.'</option>';
}
if(empty($select))showmsg('请先到后台分站设置，填写可选分站域名',3);
?>
		<div class="panel panel-primary table-responsive">
            <div class="panel-heading">
                自助开通分站
            </div>
            <div class="panel-body">

                <form class="alert alert-info">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                二级域名
                            </div>
                            <input type="text" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')" name="qz"
                                   class="form-control" required data-parsley-length="[2,8]"
                                   placeholder="输入你想要的二级前缀">
                            <div class="input-group-addon"><select name="domain">
                                                                <?php echo $select?>
                                                            </select></div>
                        </div>
                    </div>
					<div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                用户名
                            </div>
                            <input type="text" name="user" class="form-control" required placeholder="输入要注册的用户名">
                        </div>
                    </div>
					<div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                密码
                            </div>
                            <input type="text" name="pwd" class="form-control" required placeholder="输入管理员密码">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                网站名称
                            </div>
                            <input type="text" name="name" class="form-control" required
                                   data-parsley-length="[2,10]"
                                   placeholder="输入网站名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">
                                你的QQ号
                            </div>
                            <input type="number" name="qq" class="form-control" required
                                   data-parsley-length="[5,10]"
                                   placeholder="输入QQ号" value="">
                        </div>
                    </div>
					<div id="pay_frame" class="form-group text-center" style="display:none;">
					<div class="form-group">
						<div class="input-group">
						<div class="input-group-addon">订单号</div>
						<input class="form-control" name="orderid" id="orderid" value="" disabled>
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
						<div class="input-group-addon">共需支付</div>
						<input class="form-control" name="needs" id="needs" value="" disabled>
						</div>
					</div>
					<div class="alert alert-success">订单保存成功，请点击以下链接支付！</div>
<?php
if($conf['alipay_api'])echo '<button type="button" class="btn btn-default" id="buy_alipay"><img src="../assets/icon/alipay.ico" class="logo">支付宝</button>&nbsp;';
if($conf['qqpay_api'])echo '<button type="button" class="btn btn-default" id="buy_qqpay"><img src="../assets/icon/qqpay.ico" class="logo">QQ钱包</button>&nbsp;';
if($conf['wxpay_api'])echo '<button type="button" class="btn btn-default" id="buy_wxpay"><img src="../assets/icon/wechat.ico" class="logo">微信支付</button>&nbsp;';
if($conf['tenpay_api'])echo '<button type="button" class="btn btn-default" id="buy_tenpay"><img src="../assets/icon/tenpay.ico" class="logo">财付通</button>&nbsp;';
?>
					</div>
                    <input type="button" id="submit_buy" value="点此立即拥有分站" class="btn btn-danger btn-block">
                </form>
            </div>
        </div>
<script src="//cdn.bootcss.com/layer/3.0.1/layer.min.js"></script>
<script>
$(document).ready(function(){
    $("input[name='qz']").blur(function(){
        var qz = $(this).val();
        var domain = $("select[name='domain']").val();
        if(qz){
            $.get("?act=check", { 'qz' : qz , 'domain' : domain},function(data){
                    if( data == 1 ){
                        layer.alert('你所填写的域名已被使用，请更换一个！');
						//$("input[name='qz']").focus();
                    }
            });
        }
    });
	$("input[name='user']").blur(function(){
        var user = $(this).val();
        if(user){
            $.get("?act=check2", { 'user' : user},function(data){
                    if( data == 1 ){
                        layer.alert('你所填写的用户名已存在！');
						//$("input[name='user']").focus();
                    }
            });
        }
    });
	$("#submit_buy").click(function(){
		var qz = $("input[name='qz']").val();
		var domain = $("select[name='domain']").val();
		var name = $("input[name='name']").val();
		var qq = $("input[name='qq']").val();
		var user = $("input[name='user']").val();
		var pwd = $("input[name='pwd']").val();
		if(qz=='' || name=='' || qq=='' || user=='' || pwd==''){layer.alert('请确保每项不能为空！');return false;}
		if(qz.length<2){
			layer.alert('域名前缀太短！'); return false;
		}else if(qz.length>10){
			layer.alert('域名前缀太长！'); return false;
		}else if(name.length<2){
			layer.alert('网站名称太短！'); return false;
		}else if(qq.length<5){
			layer.alert('QQ格式不正确！'); return false;
		}else if(user.length<3){
			layer.alert('用户名太短'); return false;
		}else if(user.length>20){
			layer.alert('用户名太长'); return false;
		}else if(pwd.length<6){
			layer.alert('密码不能低于6位'); return false;
		}else if(pwd.length>30){
			layer.alert('密码太长'); return false;
		}
		$('#pay_frame').hide();
		$('#submit_buy').val('Loading');
		$.ajax({
			type : "POST",
			url : "reg.php?act=pay",
			data : {qz:qz,domain:domain,name:name,qq:qq,user:user,pwd:pwd},
			dataType : 'json',
			success : function(data) {
				if(data.code == 0){
					$('#alert_frame').hide();
					$('#submit_buy').hide();
					$('#orderid').val(data.trade_no);
					$('#needs').val("￥"+data.need);
					$("#pay_frame").slideDown();
				}else{
					layer.alert(data.msg);
				}
				$('#submit_buy').val('立即购买');
			} 
		});
	});
$("#buy_alipay").click(function(){
	var orderid=$("#orderid").val();
	window.location.href='../other/submit.php?type=alipay&orderid='+orderid;
});
$("#buy_qqpay").click(function(){
	var orderid=$("#orderid").val();
	window.location.href='../other/submit.php?type=qqpay&orderid='+orderid;
});
$("#buy_wxpay").click(function(){
	var orderid=$("#orderid").val();
	window.location.href='../other/submit.php?type=wxpay&orderid='+orderid;
});
$("#buy_tenpay").click(function(){
	var orderid=$("#orderid").val();
	window.location.href='../other/submit.php?type=tenpay&orderid='+orderid;
});
});
</script>
