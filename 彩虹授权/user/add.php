<?php
/**
 * 添加授权
**/
$mod='blank';
include("../api.inc.php");
$title='添加授权';
include './head.php';
$id=$user['id'];
if($userlogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$row=$DB->get_row("SELECT * FROM auth_site WHERE id='$id' limit 1");
$rs=$DB->get_row("SELECT * FROM auth_config WHERE 1");
$vip=$row['vip'];
if($vip==1){
	$dljg=$rs['vip1'];
}elseif($vip==0){
	$dljg=$rs['vip0'];
}elseif($vip==2){
	$dljg=$rs['vip2'];
}
?>
<?php
if(isset($_POST['qq']) && isset($_POST['url'])){
$qq=daddslashes($_POST['qq']);
$user=daddslashes($_POST['user']);
$pass=daddslashes($_POST['pass']);
$url=daddslashes($_POST['url']);
$rmb=$row['rmb']-$dljg*1;
if($rmb>=0){
	
}else{
	exit("<script language='javascript'>alert('您的余额不足，请联系管理员充值！');history.go(-1);</script>");
}
$sql=$DB->query("update `auth_site` set `rmb`='$rmb' where `id`=$id;");
if($sql){
	
}else{
	exit("<script language='javascript'>alert('扣款失败，请联系管理员！');history.go(-1);</script>");
}
$row=$DB->get_row("SELECT * FROM auth_site WHERE user='{$user}' limit 1");
if($row!='')exit("<script language='javascript'>alert('授权平台已存在该账号，请使用别的');history.go(-1);</script>");
$row1=$DB->get_row("SELECT * FROM auth_site WHERE 1 order by sign desc limit 1");
$sign=$row1['sign']+1;
$authcode=md5(random(32).$qq);
$row2=$DB->get_row("SELECT * FROM auth_site WHERE authcode='{$authcode}' limit 1");
if($row!='')exit("<script language='javascript'>alert('请返回重新操作！');history.go(-1);</script>");
$url_arr=explode(',',$url);
foreach($url_arr as $val) {
	$sql="insert into `auth_site` (`zid`,`uid`,`user`,`pass`,`rmb`,`vip`,`url`,`date`,`authcode`,`active`,`sign`) values ('".$user['id']."','".$qq."','".$user."','".$pass."','0','0','".trim($val)."','".$date."','".$authcode."','1','".$sign."')";
	$DB->query($sql);
}
exit("<script language='javascript'>alert('添加授权成功！');window.location.href='downfile.php?qq={$qq}';</script>");
} ?>
	<div class="panel panel-default">
<div class="panel-heading">
	<h2 class="panel-title">添加授权</h2>当前授权价格<?=$dljg?>元
	</div>
				<div class="panel-body">
                    <table class="table table-bordered table-condensed table-hover table-striped table-vertical-center">
          <form action="./add.php" method="post" class="form-horizontal" role="form">
            <div class="input-group">
              <span class="input-group-addon">ＱＱ</span>
              <input type="text" name="qq" value="<?=@$_POST['qq']?>" class="form-control" placeholder="购买授权人的ＱＱ" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">账号</span>
              <input type="text" name="user" value="<?=@$_POST['user']?>" class="form-control" placeholder="购买授权的账号" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">密码</span>
              <input type="text" name="pass" value="<?=@$_POST['pass']?>" class="form-control" placeholder="购买授权的密码" autocomplete="off" required/>
            </div><br/>
            <div class="input-group">
              <span class="input-group-addon">域名</span>
              <input type="text" name="url" value="<?=@$_POST['url']?>" class="form-control" placeholder="购买授权的域名" autocomplete="off" required/>
            </div><br/>
			 <div class="list-group-item">
			 <button class="btn btn-info btn-block" type="submit" value="添加">确认添加</button>
			 </div>
          </form>
        </div>
        <div class="panel-footer">
          <span class="glyphicon glyphicon-info-sign"></span> 添多个域名请用英文逗号 , 隔开！
        </div>
      </div>
    </div>
  </div>