<?php
$mod='blank';
include("../api.inc.php");
$title='支付配置';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
if($udata['per_db']==0) {
	showmsg('您的账号没有权限使用此功能',3);
	exit;
}
$mod=isset($_GET['mod'])?$_GET['mod']:null;
if($mod=='pay'){
	saveSetting('alipay_api',$_POST['alipay_api']);
	saveSetting('alipay_pid',$_POST['alipay_pid']);
	saveSetting('pay_pid',$_POST['pay_pid']);
	saveSetting('pay_key',$_POST['pay_key']);
	saveSetting('ptmoney',number_format($_POST['ptmoney'],2));
	saveSetting('money',number_format($_POST['money'],2));
	saveSetting('tittle',$_POST['tittle']);//网站标题
	saveSetting('webqq',$_POST['webqq']);//网站QQ
	saveSetting('pay_api',$_POST['pay_api']);//支付API
	$ad=$CACHE->clear();
	if($ad){showmsg('修改成功！',1);
		$city=get_ip_city($clientip);
		$DB->query("insert into `auth_log` (`uid`,`type`,`date`,`city`,`data`) values ('".$udata['user']."','修改支付','".$date."','".$city."','无')");

	}else showmsg('修改失败！<br/>'.$DB->error(),4);
}elseif($mod==''){
?>
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">支付设置</h3></div>
          <div class="panel-body">
          <form action="./pay.php?mod=pay" method="post" class="form-horizontal" role="form">
			<div class="input-group">
              <span class="input-group-addon">开启关闭</span>
			<select class="form-control" name="alipay_api" default="<?php echo $confs['alipay_api']?>"><option value="0">关闭</option><option value="2">易支付免签约接口</option></select>
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">商户ID</span>
			<input type="text" name="pay_pid" class="form-control"
				   value="<?php echo $confs['pay_pid']?>">
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">商户KEY</span>
				<input type="text" name="pay_key" class="form-control" value="<?php echo $confs['pay_key']?>">
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">代理授权价格</span>
			<input type="text" name="money" class="form-control" value="<?php echo $confs['money']?>">
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">普通授权价格</span>
			<input type="text" name="ptmoney" class="form-control" value="<?php echo $confs['ptmoney']?>">
            </div><br/>
			<div class="input-group">
              <span class="input-group-addon">网站标题</span>
			<input type="text" name="tittle" class="form-control" value="<?php echo $confs['tittle']?>">
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">网站QQ</span>
			<input type="text" name="webqq" class="form-control" value="<?php echo $confs['webqq']?>">
            </div><br/>
			<div class="input-group">
			<span class="input-group-addon">支付API</span>
			<input type="text" name="pay_api" class="form-control" value="<?php echo $confs['pay_api']?>">
            </div><br/>
            <div class="list-group-item">
            <button class="btn btn-primary form-control" type="submit" name="submit" value="修改">确认保存</button>
            </div>
	</div>
  </form>
</div>
</div>
    </div>
<script>
$("select[name=\'alipay_api\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_01").css("display","inherit");
	}else{
		$("#payapi_01").css("display","none");
	}
});
$("select[name=\'tenpay_api\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_03").css("display","inherit");
	}else{
		$("#payapi_03").css("display","none");
	}
});
$("select[name=\'wxpay_api\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_04").css("display","inherit");
	}else{
		$("#payapi_04").css("display","none");
	}
});
$("select[name=\'qqpay_api\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_05").css("display","inherit");
	}else{
		$("#payapi_05").css("display","none");
	}
});
$("select[name=\'alipay2_api\']").change(function(){
	if($(this).val() == 1){
		$("#payapi_02").css("display","inherit");
	}else{
		$("#payapi_02").css("display","none");
	}
});
</script>
 <?php 
}
?>

<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default"));
}
</script>
    </div>
  </div>